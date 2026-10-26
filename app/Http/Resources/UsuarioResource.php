<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsuarioResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'nombre'           => $this->nombre,
            'apellido'         => $this->apellido,
            'dni'              => $this->dni,
            'telefono'         => $this->telefono,
            'direccion'        => $this->direccion,
            'genero'           => $this->genero,
            'fecha_nacimiento' => $this->fecha_nacimiento?->toDateString(),
            'email'            => $this->email,
            'cliente'          => new ClienteResource($this->whenLoaded('cliente')),
            'empleado'         => new EmpleadoResource($this->whenLoaded('empleado')),
        ];
    }
}
