<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmpleadoRequest;
use App\Http\Resources\EmpleadoResource;
use App\Models\Empleado;

class EmpleadoController extends Controller
{
    public function index()
    {
        return EmpleadoResource::collection(Empleado::with(['usuario'])->paginate(10));
    }

    public function show(Empleado $empleado)
    {
        return new EmpleadoResource($empleado->load(['usuario']));
    }

    public function store(EmpleadoRequest $request)
    {
        return (new EmpleadoResource(Empleado::create($request->validated())))
            ->response()
            ->setStatusCode(201);
    }

    public function update(EmpleadoRequest $request, Empleado $empleado)
    {
        $empleado->update($request->validated());

        return new EmpleadoResource($empleado);
    }

    public function destroy(Empleado $empleado)
    {
        $empleado->delete();

        return response()->noContent();
    }
}