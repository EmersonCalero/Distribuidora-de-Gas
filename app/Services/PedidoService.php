<?php

namespace App\Services;

use App\Exceptions\ReglaNegocioException;
use App\Models\Balon;
use App\Models\Cliente;
use App\Models\Pedido;
use Illuminate\Support\Facades\DB;

class PedidoService
{
    public function __construct(private StockService $stock)
    {
    }

    public function crear(array $d): Pedido
    {
        $cliente = $this->validarCliente($d['cliente_id']);
        $balon = Balon::findOrFail($d['balon_id']);

        if ($balon->estado !== 'activo') {
            throw new ReglaNegocioException('El balón solicitado está inactivo.');
        }

        return Pedido::create([
            'cliente_id'      => $cliente->id,
            'empleado_id'     => $d['empleado_id'],
            'balon_id'        => $balon->id,
            'cantidad'        => $d['cantidad'],
            'precio_unitario' => $balon->precio_venta,
            'total'           => round($d['cantidad'] * $balon->precio_venta, 2),
            'estado'          => 'pendiente',
        ])->load(['cliente', 'balon']);
    }

    /** Modifica un pedido solo mientras esté pendiente; recalcula precio y total. */
    public function actualizar(Pedido $pedido, array $d): Pedido
    {
        return DB::transaction(function () use ($pedido, $d) {
            $pedido = Pedido::lockForUpdate()->findOrFail($pedido->id);

            if ($pedido->estado !== 'pendiente') {
                throw new ReglaNegocioException('RN-09: solo puede modificarse un pedido pendiente.');
            }

            $balon = Balon::findOrFail($d['balon_id'] ?? $pedido->balon_id);
            $cantidad = $d['cantidad'] ?? $pedido->cantidad;

            $pedido->update([
                'empleado_id'     => $d['empleado_id'] ?? $pedido->empleado_id,
                'balon_id'        => $balon->id,
                'cantidad'        => $cantidad,
                'precio_unitario' => $balon->precio_venta,
                'total'           => round($cantidad * $balon->precio_venta, 2),
            ]);

            return $pedido->load(['cliente', 'balon']);
        });
    }

    /** Confirma el pedido y descuenta el stock de balones llenos del almacén indicado. */
    public function confirmar(Pedido $pedido, int $almacenId, int $userId): Pedido
    {
        return DB::transaction(function () use ($pedido, $almacenId, $userId) {
            $pedido = Pedido::lockForUpdate()->findOrFail($pedido->id);

            if ($pedido->estado !== 'pendiente') {
                throw new ReglaNegocioException('Solo puede confirmarse un pedido pendiente.');
            }

            $this->validarCliente($pedido->cliente_id); // RN-05

            $this->stock->mover([                        // RN-01, RN-02, RN-03
                'balon_id'     => $pedido->balon_id,
                'almacen_id'   => $almacenId,
                'user_id'      => $userId,
                'pedido_id'    => $pedido->id,
                'tipo'         => 'salida',
                'estado_balon' => 'lleno',
                'cantidad'     => $pedido->cantidad,
                'motivo'       => 'pedido',
            ]);

            $pedido->update(['estado' => 'confirmado']);

            return $pedido->load(['cliente', 'balon']);
        });
    }

    private function validarCliente(int $clienteId): Cliente
    {
        $cliente = Cliente::with('usuario')->findOrFail($clienteId);
        $u = $cliente->usuario;

        $completo = $cliente->activo && $u
            && filled($u->nombre) && filled($u->telefono) && filled($cliente->direccion);

        if (! $completo) {
            throw new ReglaNegocioException('RN-05: el cliente está inactivo o tiene datos incompletos (nombre, teléfono o dirección).');
        }

        return $cliente;
    }
}
