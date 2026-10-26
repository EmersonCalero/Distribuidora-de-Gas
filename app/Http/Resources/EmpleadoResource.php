<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmpleadoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'turno'        => $this->turno,
            'cargo'        => $this->cargo,
            'hora_ingreso' => $this->hora_ingreso,
            'hora_salida'  => $this->hora_salida,
            'activo'       => $this->activo,
            'usuario'      => new UsuarioResource($this->whenLoaded('usuario')),
            'rutas'        => RutaEntregaResource::collection($this->whenLoaded('rutas')),
        ];
    }
}
