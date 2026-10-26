<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AjusteStockRequest;
use App\Http\Resources\MovimientoStockResource;
use App\Http\Resources\StockBalonResource;
use App\Models\StockBalon;
use App\Services\StockService;

class StockBalonController extends Controller
{
    public function index()
    {
        return StockBalonResource::collection(StockBalon::with(['balon', 'almacen'])->paginate(10));
    }

    public function show(StockBalon $stockBalon)
    {
        return new StockBalonResource($stockBalon->load(['balon', 'almacen']));
    }

    public function ajustar(AjusteStockRequest $request, StockService $stock)
    {
        $movimiento = $stock->mover($request->validated() + ['motivo' => 'ajuste']);

        return (new MovimientoStockResource($movimiento))->response()->setStatusCode(201);
    }
}