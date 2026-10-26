<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BalonRequest;
use App\Http\Resources\BalonResource;
use App\Models\Balon;

class BalonController extends Controller
{
    public function index()
    {
        return BalonResource::collection(Balon::with(['almacenes'])->paginate(10));
    }

    public function show(Balon $balon)
    {
        return new BalonResource($balon->load(['almacenes']));
    }

    public function store(BalonRequest $request)
    {
        return (new BalonResource(Balon::create($request->validated())))
            ->response()
            ->setStatusCode(201);
    }

    public function update(BalonRequest $request, Balon $balon)
    {
        $balon->update($request->validated());

        return new BalonResource($balon);
    }

    public function destroy(Balon $balon)
    {
        $balon->delete();

        return response()->noContent();
    }
}