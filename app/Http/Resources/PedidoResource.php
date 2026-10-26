<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PedidoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'cantidad'        => $this->cantidad,
            'precio_unitario' => $this->precio_unitario,
            'total'           => $this->total,
            'estado'          => $this->estado,
            'fecha'           => $this->created_at?->toIso8601String(),
            'cliente'         => new ClienteResource($this->whenLoaded('cliente')),
            'empleado'        => new EmpleadoResource($this->whenLoaded('empleado')),
            'balon'           => new BalonResource($this->whenLoaded('balon')),
            'ruta'            => new RutaEntregaResource($this->whenLoaded('rutaEntrega')),
            'entrega'         => new EntregaResource($this->whenLoaded('entrega')),
        ];
    }
}
