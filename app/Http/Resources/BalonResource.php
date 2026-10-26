<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BalonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'codigo'       => $this->codigo,
            'marca'        => $this->marca,
            'capacidad_kg' => $this->capacidad_kg,
            'precio_venta' => $this->precio_venta,
            'estado'       => $this->estado,
            'stock'        => $this->whenPivotLoaded('stock_balones', fn () => [
                'llenos' => $this->pivot->stock_llenos,
                'vacios' => $this->pivot->stock_vacios,
            ]),
            'almacenes'    => AlmacenResource::collection($this->whenLoaded('almacenes')),
        ];
    }
}
