<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehiculo extends Model
{
    protected $table = 'vehiculos';

    protected $fillable = ['placa', 'marca', 'modelo', 'capacidad_balones', 'estado'];

    public function rutas(): HasMany
    {
        return $this->hasMany(RutaEntrega::class);
    }
}