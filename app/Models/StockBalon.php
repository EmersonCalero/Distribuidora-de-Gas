<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockBalon extends Model
{
    protected $table = 'stock_balones';

    protected $fillable = ['balon_id', 'almacen_id', 'stock_llenos', 'stock_vacios'];

    public function balon(): BelongsTo
    {
        return $this->belongsTo(Balon::class);
    }

    public function almacen(): BelongsTo
    {
        return $this->belongsTo(Almacen::class);
    }
}
