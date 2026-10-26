<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Entrega extends Model
{
    protected $table = 'entregas';

    protected $fillable = ['pedido_id', 'ruta_id', 'balones_entregados', 'balones_retornados', 'observaciones'];

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    public function rutaEntrega(): BelongsTo
    {
        return $this->belongsTo(RutaEntrega::class, 'ruta_id');
    }
}
