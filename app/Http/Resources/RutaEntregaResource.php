<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RutaEntregaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'numero_ruta'           => $this->numero_ruta,
            'zona'                  => $this->zona,
            'ubicaciones_incluidas' => $this->ubicaciones_incluidas,
            'estado'                => $this->estado,
            'fecha_inicio'          => $this->fecha_inicio?->toIso8601String(),
            'fecha_fin'             => $this->fecha_fin?->toIso8601String(),
            'pedidos_planificados'  => $this->pedidos_planificados,
            'pedidos_completados'   => $this->pedidos_completados,
            'vehiculo'              => new VehiculoResource($this->whenLoaded('vehiculo')),
            'empleado'              => new EmpleadoResource($this->whenLoaded('empleado')),
            'pedidos'               => PedidoResource::collection($this->whenLoaded('pedidos')),
            'entregas'              => EntregaResource::collection($this->whenLoaded('entregas')),
        ];
    }
}
