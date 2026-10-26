<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProveedorRequest;
use App\Http\Resources\ProveedorResource;
use App\Models\Proveedor;

class ProveedorController extends Controller
{
    public function index()
    {
        return ProveedorResource::collection(Proveedor::paginate(10));
    }

    public function show(Proveedor $proveedor)
    {
        return new ProveedorResource($proveedor->load([]));
    }

    public function store(ProveedorRequest $request)
    {
        return (new ProveedorResource(Proveedor::create($request->validated())))
            ->response()
            ->setStatusCode(201);
    }

    public function update(ProveedorRequest $request, Proveedor $proveedor)
    {
        $proveedor->update($request->validated());

        return new ProveedorResource($proveedor);
    }

    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();

        return response()->noContent();
    }
}