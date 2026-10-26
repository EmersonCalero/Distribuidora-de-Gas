<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Balon extends Model
{
    protected $table = 'balones';

    protected $fillable = ['codigo', 'marca', 'capacidad_kg', 'precio_venta', 'estado'];

    protected $casts = ['capacidad_kg' => 'decimal:2', 'precio_venta' => 'decimal:2'];

    protected static function booted(): void
    {
        // RN-04: no se elimina un balón con movimientos registrados
        static::deleting(function (Balon $balon) {
            if ($balon->movimientos()->exists()) {
                throw new \RuntimeException('RN-04: el balón tiene movimientos; cambie su estado a inactivo.');
            }
        });
    }

    public function almacenes(): BelongsToMany
    {
        return $this->belongsToMany(Almacen::class, 'stock_balones')
            ->withPivot('stock_llenos', 'stock_vacios')
            ->withTimestamps();
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(StockBalon::class);
    }

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class);
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