<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ReglaNegocioException;
use App\Http\Controllers\Controller;
use App\Http\Requests\RutaEntregaRequest;
use App\Http\Resources\RutaEntregaResource;
use App\Models\RutaEntrega;
use App\Services\RutaService;

class RutaEntregaController extends Controller
{
    public function index()
    {
        return RutaEntregaResource::collection(
            RutaEntrega::with(['vehiculo', 'empleado'])->latest()->paginate(10)
        );
    }

    public function show(RutaEntrega $rutaEntrega)
    {
        return new RutaEntregaResource(
            $rutaEntrega->load(['vehiculo', 'empleado.usuario', 'pedidos.cliente'])
        );
    }

    public function store(RutaEntregaRequest $request)
    {
        return (new RutaEntregaResource(RutaEntrega::create($request->validated())))
            ->response()
            ->setStatusCode(201);
    }

    public function update(RutaEntregaRequest $request, RutaEntrega $rutaEntrega)
    {
        if ($rutaEntrega->estado !== 'planificada') {
            throw new ReglaNegocioException('Solo puede modificarse una ruta planificada.');
        }

        $rutaEntrega->update($request->validated());

        return new RutaEntregaResource($rutaEntrega->load(['vehiculo', 'empleado']));
    }

    public function destroy(RutaEntrega $rutaEntrega)
    {
        if ($rutaEntrega->estado !== 'planificada' || $rutaEntrega->pedidos()->exists()) {
            throw new ReglaNegocioException('Solo puede eliminarse una ruta planificada y sin pedidos.');
        }

        $rutaEntrega->delete();

        return response()->noContent();
    }

    public function iniciar(RutaEntrega $rutaEntrega, RutaService $rutas)
    {
        return new RutaEntregaResource($rutas->iniciar($rutaEntrega));
    }

    public function completar(RutaEntrega $rutaEntrega, RutaService $rutas)
    {
        return new RutaEntregaResource($rutas->completar($rutaEntrega));
    }
}
