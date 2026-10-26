<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockBalonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'stock_llenos' => $this->stock_llenos,
            'stock_vacios' => $this->stock_vacios,
            'balon'        => new BalonResource($this->whenLoaded('balon')),
            'almacen'      => new AlmacenResource($this->whenLoaded('almacen')),
        ];
    }
}
