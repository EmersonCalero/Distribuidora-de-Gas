<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProveedorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'ruc'          => $this->ruc,
            'razon_social' => $this->razon_social,
            'telefono'     => $this->telefono,
            'direccion'    => $this->direccion,
        ];
    }
}
