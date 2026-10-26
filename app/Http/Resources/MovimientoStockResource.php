<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovimientoStockResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'tipo'         => $this->tipo,
            'estado_balon' => $this->estado_balon,
            'cantidad'     => $this->cantidad,
            'motivo'       => $this->motivo,
            'observacion'  => $this->observacion,
            'pedido_id'    => $this->pedido_id,
            'fecha'        => $this->created_at?->toIso8601String(),
            'balon'        => new BalonResource($this->whenLoaded('balon')),
            'almacen'      => new AlmacenResource($this->whenLoaded('almacen')),
            'proveedor'    => new ProveedorResource($this->whenLoaded('proveedor')),
            'registrado_por' => $this->whenLoaded('user', fn () => [
                'id'     => $this->user->id,
                'nombre' => $this->user->name,
            ]),
        ];
    }
}
