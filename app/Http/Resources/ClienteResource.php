<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClienteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'tipo_cliente'        => $this->tipo_cliente,
            'es_frecuente'        => $this->es_frecuente,
            'zona'                => $this->zona,
            'direccion'           => $this->direccion,
            'dia_compra_habitual' => $this->dia_compra_habitual,
            'notas'               => $this->notas,
            'activo'              => $this->activo,
            'usuario'             => new UsuarioResource($this->whenLoaded('usuario')),
            'pedidos'             => PedidoResource::collection($this->whenLoaded('pedidos')),
        ];
    }
}
