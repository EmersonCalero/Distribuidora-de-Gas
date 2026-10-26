<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompraRequest;
use App\Http\Resources\CompraResource;
use App\Models\Compra;
use App\Services\CompraService;

class CompraController extends Controller
{
    public function index()
    {
        return CompraResource::collection(
            Compra::with(['proveedor', 'balon', 'almacen'])->latest()->paginate(10)
        );
    }

    public function show(Compra $compra)
    {
        return new CompraResource($compra->load(['proveedor', 'balon', 'almacen']));
    }

    public function store(CompraRequest $request, CompraService $servicio)
    {
        return (new CompraResource($servicio->registrar($request->validated())))
            ->response()
            ->setStatusCode(201);
    }
}