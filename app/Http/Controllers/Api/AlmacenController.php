<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AlmacenRequest;
use App\Http\Resources\AlmacenResource;
use App\Models\Almacen;

class AlmacenController extends Controller
{
    public function index()
    {
        return AlmacenResource::collection(Almacen::with(['balones'])->paginate(10));
    }

    public function show(Almacen $almacen)
    {
        return new AlmacenResource($almacen->load(['balones']));
    }

    public function store(AlmacenRequest $request)
    {
        return (new AlmacenResource(Almacen::create($request->validated())))
            ->response()
            ->setStatusCode(201);
    }

    public function update(AlmacenRequest $request, Almacen $almacen)
    {
        $almacen->update($request->validated());

        return new AlmacenResource($almacen);
    }

    public function destroy(Almacen $almacen)
    {
        $almacen->delete();

        return response()->noContent();
    }
}