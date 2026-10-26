<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConfirmarPedidoRequest;
use App\Http\Requests\PedidoRequest;
use App\Http\Resources\PedidoResource;
use App\Models\Pedido;
use App\Services\PedidoService;
use App\Services\RutaService;

class PedidoController extends Controller
{
    public function __construct(private PedidoService $pedidos, private RutaService $rutas)
    {
    }

    public function index()
    {
        return PedidoResource::collection(
            Pedido::with(['cliente', 'empleado', 'balon'])->latest()->paginate(10)
        );
    }

    public function show(Pedido $pedido)
    {
        return new PedidoResource(
            $pedido->load(['cliente.usuario', 'empleado', 'balon', 'rutaEntrega', 'entrega'])
        );
    }

    public function store(PedidoRequest $request)
    {
        return (new PedidoResource($this->pedidos->crear($request->validated())))
            ->response()
            ->setStatusCode(201);
    }

    public function update(PedidoRequest $request, Pedido $pedido)
    {
        return new PedidoResource($this->pedidos->actualizar($pedido, $request->validated()));
    }

    public function confirmar(ConfirmarPedidoRequest $request, Pedido $pedido)
    {
        return new PedidoResource(
            $this->pedidos->confirmar($pedido, $request->integer('almacen_id'), $request->integer('user_id'))
        );
    }

    public function asignarRuta(Pedido $pedido)
    {
        return new PedidoResource($this->rutas->asignar($pedido));
    }

    public function porAsignar()
    {
        return PedidoResource::collection($this->rutas->pedidosPorAsignar());
    }
}
