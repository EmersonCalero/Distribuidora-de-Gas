<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pedido extends Model
{
    protected $table = 'pedidos';

    protected $fillable = ['cliente_id', 'empleado_id', 'balon_id', 'ruta_id', 'cantidad', 'precio_unitario', 'total', 'estado'];

    protected $casts = ['precio_unitario' => 'decimal:2', 'total' => 'decimal:2'];

    protected static function booted(): void
    {
        // RN-09: el historial de pedidos no se elimina ni se modifica
        static::deleting(fn () => throw new \RuntimeException('RN-09: los pedidos no pueden eliminarse.'));

        static::updating(function (Pedido $pedido) {
            if (in_array($pedido->getOriginal('estado'), ['entregado', 'cancelado'])) {
                throw new \RuntimeException('RN-09: un pedido cerrado no puede modificarse.');
            }
        });
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }

    public function balon(): BelongsTo
    {
        return $this->belongsTo(Balon::class);
    }

    public function rutaEntrega(): BelongsTo
    {
        return $this->belongsTo(RutaEntrega::class, 'ruta_id');
    }

    public function entrega(): HasOne
    {
        return $this->hasOne(Entrega::class);
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(MovimientoStock::class);
    }
}
