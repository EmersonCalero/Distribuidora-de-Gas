<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UsuarioRequest;
use App\Http\Resources\UsuarioResource;
use App\Models\Usuario;

class UsuarioController extends Controller
{
    public function index()
    {
        return UsuarioResource::collection(Usuario::with(['cliente','empleado'])->paginate(10));
    }

    public function show(Usuario $usuario)
    {
        return new UsuarioResource($usuario->load(['cliente','empleado']));
    }

    public function store(UsuarioRequest $request)
    {
        return (new UsuarioResource(Usuario::create($request->validated())))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UsuarioRequest $request, Usuario $usuario)
    {
        $usuario->update($request->validated());

        return new UsuarioResource($usuario);
    }

    public function destroy(Usuario $usuario)
    {
        $usuario->delete();

        return response()->noContent();
    }
}