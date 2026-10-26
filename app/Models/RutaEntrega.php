<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RutaEntrega extends Model
{
    protected $table = 'rutas_entrega';

    protected $fillable = [
        'numero_ruta', 'zona', 'ubicaciones_incluidas', 'vehiculo_id', 'empleado_id',
        'estado', 'fecha_inicio', 'fecha_fin', 'pedidos_planificados', 'pedidos_completados',
    ];

    protected $casts = ['fecha_inicio' => 'datetime', 'fecha_fin' => 'datetime'];

    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class);
    }

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class, 'ruta_id');
    }

    public function entregas(): HasMany
    {
        return $this->hasMany(Entrega::class, 'ruta_id');
    }
}
