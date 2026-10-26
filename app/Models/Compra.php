<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Compra extends Model
{
    protected $table = 'compras';

    protected $fillable = ['proveedor_id', 'balon_id', 'almacen_id', 'cantidad', 'precio_unitario', 'total'];

    protected $casts = ['precio_unitario' => 'decimal:2', 'total' => 'decimal:2'];

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function balon(): BelongsTo
    {
        return $this->belongsTo(Balon::class);
    }

    public function almacen(): BelongsTo
    {
        return $this->belongsTo(Almacen::class);
    }
}
