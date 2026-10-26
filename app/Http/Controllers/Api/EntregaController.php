<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EntregaRequest;
use App\Http\Resources\EntregaResource;
use App\Models\Entrega;
use App\Services\EntregaService;

class EntregaController extends Controller
{
    public function index()
    {
        return EntregaResource::collection(
            Entrega::with(['pedido', 'rutaEntrega'])->latest()->paginate(10)
        );
    }

    public function show(Entrega $entrega)
    {
        return new EntregaResource($entrega->load(['pedido', 'rutaEntrega']));
    }

    public function store(EntregaRequest $request, EntregaService $servicio)
    {
        return (new EntregaResource($servicio->registrar($request->validated())))
            ->response()
            ->setStatusCode(201);
    }
}
