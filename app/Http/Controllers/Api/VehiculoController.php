<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VehiculoRequest;
use App\Http\Resources\VehiculoResource;
use App\Models\Vehiculo;

class VehiculoController extends Controller
{
    public function index()
    {
        return VehiculoResource::collection(Vehiculo::paginate(10));
    }

    public function show(Vehiculo $vehiculo)
    {
        return new VehiculoResource($vehiculo->load([]));
    }

    public function store(VehiculoRequest $request)
    {
        return (new VehiculoResource(Vehiculo::create($request->validated())))
            ->response()
            ->setStatusCode(201);
    }

    public function update(VehiculoRequest $request, Vehiculo $vehiculo)
    {
        $vehiculo->update($request->validated());

        return new VehiculoResource($vehiculo);
    }

    public function destroy(Vehiculo $vehiculo)
    {
        $vehiculo->delete();

        return response()->noContent();
    }
}