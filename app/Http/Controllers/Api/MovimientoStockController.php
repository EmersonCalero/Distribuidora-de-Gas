<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MovimientoStockResource;
use App\Models\MovimientoStock;

class MovimientoStockController extends Controller
{
    public function index()
    {
        return MovimientoStockResource::collection(
            MovimientoStock::with(['balon', 'almacen', 'user'])->latest()->paginate(10)
        );
    }

    public function show(MovimientoStock $movimientoStock)
    {
        return new MovimientoStockResource($movimientoStock->load(['balon', 'almacen', 'user', 'proveedor']));
    }
}