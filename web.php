<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\VentaController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});
Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

/* Rutas Helpers */
Route::get('proveedores/validar-dni', 'App\Http\Controllers\ProveedorController@validarDni')->name('proveedores.validar_dni');
Route::get('clientes/validar-dni', 'App\Http\Controllers\ClienteController@validarDni')->name('clientes.validar_dni');
Route::get('clientes/getDataCliente', 'App\Http\Controllers\ClienteController@getDataCliente')->name('clientes.getDataCliente');
Route::get('ventas/{id}/invoice', 'App\Http\Controllers\VentaController@invoice')->name('ventas.invoice');
Route::get('ventas/{id}/ticket', 'App\Http\Controllers\VentaController@generarTicket')->name('ventas.ticket');
Route::get('productos/codBarra', 'App\Http\Controllers\ProductoController@codBarra')->name('productos.codBarra');
Route::get('ventas/getDataVentasPorAnio', 'App\Http\Controllers\VentaController@getDataVentasPorAnio')->name('ventas.getDataVentasPorAnio');
Route::get('ventas/getDataventasPorDia', 'App\Http\Controllers\VentaController@getDataVentasPorDiaSemana')->name('ventas.getDataVentasPorDia');
Route::get('/generar-ticket/{idVenta}', [VentaController::class, 'generarTicket'])->name('generar.ticket');
Route::get('productos/getProductosActivos', 'App\Http\Controllers\ProductoController@getProductosActivos')->name('productos.getProductosActivos');
Route::get('productos/validar-codbarra', 'App\Http\Controllers\ProductoController@validarCodeBarra')->name('productos.validar_codbarra');
/* Rutas Resource */
Route::resource('dashboard', 'App\Http\Controllers\DashboardController');
Route::resource('proveedores', 'App\Http\Controllers\ProveedorController');
Route::resource('productos', 'App\Http\Controllers\ProductoController');
Route::resource('clientes', 'App\Http\Controllers\ClienteController');
Route::resource('entradas', 'App\Http\Controllers\EntradaController');
Route::resource('salidas', 'App\Http\Controllers\SalidaController');
Route::resource('ventas', 'App\Http\Controllers\VentaController');
Route::resource('empresa', 'App\Http\Controllers\EmpresaController');
Route::resource('categorias', 'App\Http\Controllers\CategoriasController');

/* Rutas Reportes */
Route::get('/reportes', [ReportesController::class, 'index'])->name('reportes.index');
Route::get('/reportes/inventario', [ReportesController::class, 'inventario'])->name('reportes.inventario');
Route::get('/reportes/exportar-inventario', [ReportesController::class, 'exportarInventario'])->name('reportes.exportar-inventario');
Route::get('/reportes/productos-agotados', [ReportesController::class, 'productosAgotados'])->name('reportes.productos-agotados');
Route::get('/reportes/exportar-productos-agotados', [ReportesController::class, 'exportarProductosAgotados'])->name('reportes.exportar-productos-agotados');
Route::get('/reportes/ventas-form', [ReportesController::class, 'ventasForm'])->name('reportes.ventas-form');
Route::get('/reportes/entradas', [ReportesController::class, 'entradas'])->name('reportes.entradas');
Route::get('/reportes/exportar-entradas', [ReportesController::class, 'exportarEntradas'])->name('reportes.exportar-entradas');
Route::get('/reportes/salidas', [ReportesController::class, 'salidas'])->name('reportes.salidas');
Route::get('/reportes/exportar-salidas', [ReportesController::class, 'exportarSalidas'])->name('reportes.exportar-salidas');
Route::post('/reportes/ventas', [ReportesController::class, 'ventas'])->name('reportes.ventas');
Route::get('/reportes/exportar-ventas', [ReportesController::class, 'exportarVentas'])->name('reportes.exportar-ventas');
