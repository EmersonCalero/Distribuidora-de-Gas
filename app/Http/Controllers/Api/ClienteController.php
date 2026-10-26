<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClienteRequest;
use App\Http\Resources\ClienteResource;
use App\Models\Cliente;

class ClienteController extends Controller
{
    public function index()
    {
        return ClienteResource::collection(Cliente::with(['usuario'])->paginate(10));
    }

    public function show(Cliente $cliente)
    {
        return new ClienteResource($cliente->load(['usuario']));
    }

    public function store(ClienteRequest $request)
    {
        return (new ClienteResource(Cliente::create($request->validated())))
            ->response()
            ->setStatusCode(201);
    }

    public function update(ClienteRequest $request, Cliente $cliente)
    {
        $cliente->update($request->validated());

        return new ClienteResource($cliente);
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return response()->noContent();
    }
}