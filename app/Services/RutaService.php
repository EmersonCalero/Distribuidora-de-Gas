<?php

namespace App\Services;

use App\Exceptions\ReglaNegocioException;
use App\Models\Pedido;
use App\Models\RutaEntrega;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RutaService
{
    /** Pedidos confirmados sin ruta; los clientes frecuentes primero (RN-08). */
    public function pedidosPorAsignar()
    {
        return Pedido::query()
            ->join('clientes', 'clientes.id', '=', 'pedidos.cliente_id')
            ->where('pedidos.estado', 'confirmado')
            ->whereNull('pedidos.ruta_id')
            ->orderByDesc('clientes.es_frecuente')
            ->orderBy('pedidos.created_at')
            ->select('pedidos.*')
            ->with('cliente')
            ->get();
    }

    /** RN-08: se prefiere una ruta planificada existente de la misma zona; si no hay, se crea. */
    public function asignar(Pedido $pedido): Pedido
    {
        return DB::transaction(function () use ($pedido) {
            $pedido = Pedido::with('cliente')->lockForUpdate()->findOrFail($pedido->id);

            if ($pedido->estado !== 'confirmado' || $pedido->ruta_id) {
                throw new ReglaNegocioException('Solo puede asignarse a una ruta un pedido confirmado y sin ruta.');
            }

            $zona = $pedido->cliente->zona;

            $ruta = RutaEntrega::with('vehiculo')
                ->where('estado', 'planificada')
                ->where('zona', $zona)
                ->lockForUpdate()
                ->orderBy('id')
                ->get()
                ->first(fn ($r) => $this->hayCapacidad($r, $pedido->cantidad));

            $ruta ??= RutaEntrega::create([
                'numero_ruta'           => 'R-'.now()->format('Ymd').'-'.Str::upper(Str::random(4)),
                'zona'                  => $zona,
                'ubicaciones_incluidas' => '',
            ]);

            $ruta->update([
                'ubicaciones_incluidas' => trim($ruta->ubicaciones_incluidas.PHP_EOL.$pedido->cliente->direccion),
                'pedidos_planificados'  => $ruta->pedidos_planificados + 1,
            ]);

            $pedido->update(['ruta_id' => $ruta->id]);

            return $pedido->load(['cliente', 'rutaEntrega']);
        });
    }

    public function iniciar(RutaEntrega $ruta): RutaEntrega
    {
        return DB::transaction(function () use ($ruta) {
            $ruta = RutaEntrega::with('vehiculo')->lockForUpdate()->findOrFail($ruta->id);

            if ($ruta->estado !== 'planificada') {
                throw new ReglaNegocioException('Solo puede iniciarse una ruta planificada.');
            }

            // RN-10: vehículo, conductor y horario definidos antes de procesar
            if (! $ruta->vehiculo_id || ! $ruta->empleado_id || ! $ruta->fecha_inicio) {
                throw new ReglaNegocioException('RN-10: la ruta requiere vehículo, empleado conductor y horario (fecha_inicio) antes de iniciarse.');
            }

            if ($ruta->pedidos()->doesntExist()) {
                throw new ReglaNegocioException('La ruta no tiene pedidos asignados.');
            }

            if ($ruta->pedidos()->sum('cantidad') > $ruta->vehiculo->capacidad_balones) {
                throw new ReglaNegocioException('La carga de la ruta supera la capacidad del vehículo.');
            }

            // RN-07: ni el vehículo ni el conductor pueden tener otra entrega activa
            $ocupada = RutaEntrega::where('estado', 'en_transito')
                ->where('id', '!=', $ruta->id)
                ->where(fn ($q) => $q->where('vehiculo_id', $ruta->vehiculo_id)
                    ->orWhere('empleado_id', $ruta->empleado_id))
                ->exists();

            if ($ocupada || $ruta->vehiculo->estado !== 'disponible') {
                throw new ReglaNegocioException('RN-07: el vehículo o el conductor ya tienen una entrega activa.');
            }

            $ruta->vehiculo->update(['estado' => 'en_ruta']);
            $ruta->update(['estado' => 'en_transito']);
            $ruta->pedidos()->where('estado', 'confirmado')->update(['estado' => 'en_ruta']);

            return $ruta->load(['vehiculo', 'empleado', 'pedidos']);
        });
    }

    public function completar(RutaEntrega $ruta): RutaEntrega
    {
        return DB::transaction(function () use ($ruta) {
            $ruta = RutaEntrega::with('vehiculo')->lockForUpdate()->findOrFail($ruta->id);

            if ($ruta->estado !== 'en_transito') {
                throw new ReglaNegocioException('Solo puede completarse una ruta en tránsito.');
            }

            if ($ruta->pedidos()->where('estado', '!=', 'entregado')->exists()) {
                throw new ReglaNegocioException('Quedan pedidos sin entregar en la ruta.');
            }

            $ruta->vehiculo->update(['estado' => 'disponible']);
            $ruta->update(['estado' => 'completada', 'fecha_fin' => now()]);

            return $ruta->load(['vehiculo', 'empleado']);
        });
    }

    private function hayCapacidad(RutaEntrega $ruta, int $cantidad): bool
    {
        if (! $ruta->vehiculo) {
            return true;
        }

        return $ruta->pedidos()->sum('cantidad') + $cantidad <= $ruta->vehiculo->capacidad_balones;
    }
}