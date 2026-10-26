<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Almacen extends Model
{
    protected $table = 'almacenes';

    protected $fillable = ['nombre', 'direccion', 'capacidad'];

    public function balones(): BelongsToMany
    {
        return $this->belongsToMany(Balon::class, 'stock_balones')
            ->withPivot('stock_llenos', 'stock_vacios')
            ->withTimestamps();
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(StockBalon::class);
    }

    public function compras(): HasMany
    {
        return $this->hasMany(Compra::class);
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(MovimientoStock::class);
    }
}
