<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EntregaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'balones_entregados' => $this->balones_entregados,
            'balones_retornados' => $this->balones_retornados,
            'observaciones'      => $this->observaciones,
            'fecha'              => $this->created_at?->toIso8601String(),
            'pedido'             => new PedidoResource($this->whenLoaded('pedido')),
            'ruta'               => new RutaEntregaResource($this->whenLoaded('rutaEntrega')),
        ];
    }
}
