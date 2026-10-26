<?php

namespace App\Services;

use App\Exceptions\ReglaNegocioException;
use App\Models\Almacen;
use App\Models\Compra;
use App\Models\StockBalon;
use Illuminate\Support\Facades\DB;

class CompraService
{
    public function __construct(private StockService $stock)
    {
    }

    public function registrar(array $d): Compra
    {
        return DB::transaction(function () use ($d) {
            $almacen = Almacen::lockForUpdate()->findOrFail($d['almacen_id']);

            // RN-11 (sugerida): el ingreso no puede superar la capacidad del almacén
            $ocupado = (int) StockBalon::where('almacen_id', $almacen->id)
                ->sum(DB::raw('stock_llenos + stock_vacios'));

            if ($ocupado + $d['cantidad'] > $almacen->capacidad) {
                throw new ReglaNegocioException('RN-11: la compra supera la capacidad del almacén.');
            }

            $compra = Compra::create([
                'proveedor_id'    => $d['proveedor_id'],
                'balon_id'        => $d['balon_id'],
                'almacen_id'      => $almacen->id,
                'cantidad'        => $d['cantidad'],
                'precio_unitario' => $d['precio_unitario'],
                'total'           => round($d['cantidad'] * $d['precio_unitario'], 2),
            ]);

            $this->stock->mover([
                'balon_id'     => $d['balon_id'],
                'almacen_id'   => $almacen->id,
                'user_id'      => $d['user_id'],
                'proveedor_id' => $d['proveedor_id'],
                'tipo'         => 'entrada',
                'estado_balon' => 'lleno',
                'cantidad'     => $d['cantidad'],
                'motivo'       => 'compra',
                'observacion'  => 'Compra #'.$compra->id,
            ]);

            return $compra->load(['proveedor', 'balon', 'almacen']);
        });
    }
}