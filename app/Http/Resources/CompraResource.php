<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompraResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'cantidad'        => $this->cantidad,
            'precio_unitario' => $this->precio_unitario,
            'total'           => $this->total,
            'fecha'           => $this->created_at?->toIso8601String(),
            'proveedor'       => new ProveedorResource($this->whenLoaded('proveedor')),
            'balon'           => new BalonResource($this->whenLoaded('balon')),
            'almacen'         => new AlmacenResource($this->whenLoaded('almacen')),
        ];
    }
}
