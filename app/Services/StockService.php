<?php

namespace App\Services;

use App\Exceptions\ReglaNegocioException;
use App\Models\MovimientoStock;
use App\Models\StockBalon;
use Illuminate\Support\Facades\DB;

class StockService
{
    /**
     * Aplica un movimiento al stock y lo registra (RN-01, RN-02, RN-03).
     * $d: balon_id, almacen_id, user_id, tipo (entrada|salida), estado_balon (lleno|vacio),
     *     cantidad, motivo, y opcionalmente proveedor_id, pedido_id y observacion.
     */
    public function mover(array $d): MovimientoStock
    {
        return DB::transaction(function () use ($d) {
            $stock = StockBalon::where('balon_id', $d['balon_id'])
                ->where('almacen_id', $d['almacen_id'])
                ->lockForUpdate()
                ->first();

            $stock ??= StockBalon::create([
                'balon_id'     => $d['balon_id'],
                'almacen_id'   => $d['almacen_id'],
                'stock_llenos' => 0,
                'stock_vacios' => 0,
            ]);

            $campo = $d['estado_balon'] === 'lleno' ? 'stock_llenos' : 'stock_vacios';
            $nuevo = $d['tipo'] === 'salida'
                ? $stock->{$campo} - $d['cantidad']
                : $stock->{$campo} + $d['cantidad'];

            if ($nuevo < 0) {
                throw new ReglaNegocioException(
                    'RN-01/RN-02: stock insuficiente; disponible '.$stock->{$campo}.', solicitado '.$d['cantidad'].'.'
                );
            }

            $stock->update([$campo => $nuevo]);

            return MovimientoStock::create($d);
        });
    }
}