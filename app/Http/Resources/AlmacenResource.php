<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlmacenResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'nombre'    => $this->nombre,
            'direccion' => $this->direccion,
            'capacidad' => $this->capacidad,
            'stock'     => $this->whenPivotLoaded('stock_balones', fn () => [
                'llenos' => $this->pivot->stock_llenos,
                'vacios' => $this->pivot->stock_vacios,
            ]),
            'balones'   => BalonResource::collection($this->whenLoaded('balones')),
        ];
    }
}
