<?php

namespace App\Services;

use App\Exceptions\ReglaNegocioException;
use App\Models\Entrega;
use App\Models\MovimientoStock;
use App\Models\Pedido;
use App\Models\RutaEntrega;
use Illuminate\Support\Facades\DB;

class EntregaService
{
    public function __construct(private StockService $stock)
    {
    }

    public function registrar(array $d): Entrega
    {
        return DB::transaction(function () use ($d) {
            $pedido = Pedido::lockForUpdate()->findOrFail($d['pedido_id']);

            if (! $pedido->ruta_id) {
                throw new ReglaNegocioException('RN-10: el pedido no tiene ruta asignada.');
            }

            $ruta = RutaEntrega::lockForUpdate()->findOrFail($pedido->ruta_id);

            if ($pedido->estado !== 'en_ruta' || $ruta->estado !== 'en_transito') {
                throw new ReglaNegocioException('RN-10: solo se entrega un pedido que está en una ruta en tránsito.');
            }

            if ((int) $d['balones_entregados'] !== (int) $pedido->cantidad) {
                throw new ReglaNegocioException('Debe entregarse la cantidad pedida ('.$pedido->cantidad.').');
            }

            $entrega = Entrega::create([
                'pedido_id'          => $pedido->id,
                'ruta_id'            => $ruta->id,
                'balones_entregados' => $d['balones_entregados'],
                'balones_retornados' => $d['balones_retornados'],
                'observaciones'      => $d['observaciones'] ?? null,
            ]);

            // RN-06: los vacíos que retornan se registran en la misma transacción
            if ($d['balones_retornados'] > 0) {
                $almacenId = MovimientoStock::where('pedido_id', $pedido->id)
                    ->where('motivo', 'pedido')
                    ->value('almacen_id');

                $this->stock->mover([
                    'balon_id'     => $pedido->balon_id,
                    'almacen_id'   => $almacenId,
                    'user_id'      => $d['user_id'],
                    'pedido_id'    => $pedido->id,
                    'tipo'         => 'entrada',
                    'estado_balon' => 'vacio',
                    'cantidad'     => $d['balones_retornados'],
                    'motivo'       => 'devolucion',
                ]);
            }

            $pedido->update(['estado' => 'entregado']);
            $ruta->increment('pedidos_completados');

            return $entrega->load('pedido');
        });
    }
}