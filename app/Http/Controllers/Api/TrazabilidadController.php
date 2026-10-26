<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MovimientoStockResource;
use App\Models\Almacen;
use App\Models\Balon;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TrazabilidadController extends Controller
{
    // GET /api/balones/{balon}/movimientos?desde=Y-m-d&hasta=Y-m-d
    public function movimientos(Request $request, Balon $balon)
    {
        $movimientos = $balon->movimientos()
            ->with(['almacen', 'user'])
            ->when($request->filled(['desde', 'hasta']), function ($q) use ($request) {
                $q->whereBetween('created_at', [
                    Carbon::parse($request->desde)->startOfDay(),
                    Carbon::parse($request->hasta)->endOfDay(),
                ]);
            })
            ->latest()
            ->get();

        return MovimientoStockResource::collection($movimientos);
    }

    // GET /api/almacenes/{almacen}/stock
    public function stock(Almacen $almacen)
    {
        $stock = $almacen->balones()->orderBy('codigo')->get()->map(fn ($b) => [
            'balon'        => $b->codigo,
            'marca'        => $b->marca,
            'stock_llenos' => $b->pivot->stock_llenos,
            'stock_vacios' => $b->pivot->stock_vacios,
        ]);

        return response()->json(['data' => $stock]);
    }
}