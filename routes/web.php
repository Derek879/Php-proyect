<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CarritoController;

use App\Models\Producto;
use App\Models\Pedido;
use App\Models\Categoria;
use App\Models\User;

Route::post('/pedidos/{pedido}/avanzar',  [PedidoController::class, 'avanzarEstado'])->name('pedidos.avanzar');
Route::post('/pedidos/{pedido}/cancelar', [PedidoController::class, 'cancelarPedido'])->name('pedidos.cancelar');
Route::post('/pedidos/{pedido}/estado',   [PedidoController::class, 'updateEstado'])->name('pedidos.updateEstado');

Route::get('/', function () {
    $stats = [
        'productos' => Producto::count(),
        'pedidos'   => Pedido::count(),
        'pagos'     => 0,
        'clientes'  => User::where('role', 'cliente')->count(),
    ];

    $categorias = Categoria::all();

    $productos = Producto::latest()->take(4)->get();

    return view('welcome', compact('stats', 'categorias', 'productos'));
});

Route::resource('productos', ProductoController::class);
Route::resource('pedidos',   PedidoController::class);
Route::resource('categorias', CategoriaController::class);
Route::resource('pagos',     PagoController::class);

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CARRITO
    Route::post('/carrito/agregar',          [CarritoController::class, 'agregar'])->name('carrito.agregar');
    Route::delete('/carrito/quitar/{id}',    [CarritoController::class, 'quitar'])->name('carrito.quitar');
    Route::post('/carrito/vaciar',           [CarritoController::class, 'vaciar'])->name('carrito.vaciar');
    Route::get('/carrito/checkout',          [CarritoController::class, 'checkout'])->name('carrito.checkout');
    Route::patch('/carrito/cantidad/{id}',   [CarritoController::class, 'actualizarCantidad'])->name('carrito.cantidad');
});

Route::get('/admin', function () {
    return view('admin.dashboard');
})->middleware('auth');

require __DIR__.'/auth.php';