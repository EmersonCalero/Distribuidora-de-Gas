<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehiculoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'placa'             => $this->placa,
            'marca'             => $this->marca,
            'modelo'            => $this->modelo,
            'capacidad_balones' => $this->capacidad_balones,
            'estado'            => $this->estado,
        ];
    }
}
