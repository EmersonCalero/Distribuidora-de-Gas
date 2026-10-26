<?php

use App\Http\Controllers\Api\AlmacenController;
use App\Http\Controllers\Api\BalonController;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\CompraController;
use App\Http\Controllers\Api\EmpleadoController;
use App\Http\Controllers\Api\EntregaController;
use App\Http\Controllers\Api\MovimientoStockController;
use App\Http\Controllers\Api\PedidoController;
use App\Http\Controllers\Api\ProveedorController;
use App\Http\Controllers\Api\RutaEntregaController;
use App\Http\Controllers\Api\StockBalonController;
use App\Http\Controllers\Api\TrazabilidadController;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\VehiculoController;
use Illuminate\Support\Facades\Route;

// 1. Catálogos: apiResource con CRUD completo
$catalogos = [
    'usuarios'    => [UsuarioController::class, 'usuario'],
    'clientes'    => [ClienteController::class, 'cliente'],
    'empleados'   => [EmpleadoController::class, 'empleado'],
    'vehiculos'   => [VehiculoController::class, 'vehiculo'],
    'proveedores' => [ProveedorController::class, 'proveedor'],
    'almacenes'   => [AlmacenController::class, 'almacen'],
    'balones'     => [BalonController::class, 'balon'],
];

foreach ($catalogos as $uri => [$controlador, $parametro]) {
    Route::apiResource($uri, $controlador)->parameters([$uri => $parametro]);
}

// 2. Acciones operativas (antes de los recursos con {parametro})
Route::get('/pedidos/por-asignar', [PedidoController::class, 'porAsignar']);
Route::post('/pedidos/{pedido}/confirmar', [PedidoController::class, 'confirmar']);
Route::post('/pedidos/{pedido}/asignar-ruta', [PedidoController::class, 'asignarRuta']);
Route::post('/rutas-entrega/{rutaEntrega}/iniciar', [RutaEntregaController::class, 'iniciar']);
Route::post('/rutas-entrega/{rutaEntrega}/completar', [RutaEntregaController::class, 'completar']);
Route::post('/stock-balones/ajustes', [StockBalonController::class, 'ajustar']);

// 3. Recursos operativos: solo las acciones que admite cada uno
$operativos = [
    'compras'           => [CompraController::class, 'compra', ['index', 'show', 'store']],
    'stock-balones'     => [StockBalonController::class, 'stockBalon', ['index', 'show']],
    'movimientos-stock' => [MovimientoStockController::class, 'movimientoStock', ['index', 'show']],
    'rutas-entrega'     => [RutaEntregaController::class, 'rutaEntrega', ['index', 'show', 'store', 'update', 'destroy']],
    'pedidos'           => [PedidoController::class, 'pedido', ['index', 'show', 'store', 'update']],
    'entregas'          => [EntregaController::class, 'entrega', ['index', 'show', 'store']],
];

foreach ($operativos as $uri => [$controlador, $parametro, $acciones]) {
    Route::apiResource($uri, $controlador)
        ->only($acciones)
        ->parameters([$uri => $parametro]);
}

// 4. Trazabilidad
Route::get('/balones/{balon}/movimientos', [TrazabilidadController::class, 'movimientos']);
Route::get('/almacenes/{almacen}/stock', [TrazabilidadController::class, 'stock']);
