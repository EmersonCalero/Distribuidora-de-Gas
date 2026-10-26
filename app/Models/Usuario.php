<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Usuario extends Model
{
    protected $table = 'usuarios';

    protected $fillable = ['nombre', 'apellido', 'dni', 'telefono', 'direccion', 'genero', 'fecha_nacimiento', 'email'];

    protected $casts = ['fecha_nacimiento' => 'date'];

    public function cliente(): HasOne
    {
        return $this->hasOne(Cliente::class);
    }

    public function empleado(): HasOne
    {
        return $this->hasOne(Empleado::class);
    }
}
