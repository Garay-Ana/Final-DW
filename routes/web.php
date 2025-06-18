<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PlatoController;
use App\Http\Controllers\ChefController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// Vista principal de bienvenida o landing
Route::get('/', function () {
    $minPrice = 1000;
    $maxPrice = 5000000; // O el valor dinámico que corresponda
    return view('welcome', compact('minPrice', 'maxPrice'));
});


Route::post('/guardar-resena', [PlatoController::class, 'guardarResena'])->name('guardarResena');

// Autenticación y registro
Route::get('/register', [RegisteredUserController::class, 'create'])->middleware('guest')->name('register');
Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('guest');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
Route::post('/admin/logout', [AuthenticatedSessionController::class, 'destroyAdmin'])->name('admin.logout')->middleware('auth');

// Selector de acceso por rol
Route::get('/acceso/{rol}', function ($rol) {
    if (!in_array($rol, ['cliente', 'chef', 'admin', 'repartidor'])) {
        abort(404);
    }
    return view('select-auth', compact('rol'));
});

// Dashboard por roles
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/cliente', fn() => view('cliente'))->name('cliente.dashboard');
    Route::get('/repartidor', [App\Http\Controllers\RepartidorController::class, 'dashboard'])->name('repartidor.dashboard');

    Route::get('/chef', [ChefController::class, 'index'])->name('chef.index');
    Route::get('/chef/pedidos', fn() => view('pedidos'))->name('chef.pedidos');
    Route::get('/chef/menu', fn() => view('menu'))->name('chef.menu');

    Route::get('/chef/plato/create', [App\Http\Controllers\ChefController::class, 'createPlato'])->name('chef.plato.create');
    Route::post('/chef/plato', [App\Http\Controllers\ChefController::class, 'storePlato'])->name('chef.plato.store');
    Route::get('/chef/inventario', fn() => view('inventario'))->name('chef.inventario');

    Route::get('/chef/plato/{id}/edit', [App\Http\Controllers\ChefController::class, 'editPlato'])->name('chef.plato.edit');
    Route::put('/chef/plato/{id}', [App\Http\Controllers\ChefController::class, 'updatePlato'])->name('chef.plato.update');

    Route::delete('/chef/plato/{id}', [App\Http\Controllers\ChefController::class, 'destroyPlato'])->name('chef.plato.destroy');
    Route::get('/chef/mandar-pedido', [App\Http\Controllers\ChefController::class, 'mandarPedido'])->name('chef.mandar-pedido');
    Route::get('/chef/inventario', [App\Http\Controllers\ChefController::class, 'inventario'])->name('chef.inventario');

    // Rutas para cliente
    Route::get('/cliente', [App\Http\Controllers\ClienteController::class, 'index'])->name('cliente.dashboard');
    Route::post('/cliente/pedido', [App\Http\Controllers\ClienteController::class, 'storePedido'])->name('cliente.pedido.store');

    // Nuevas rutas para carrito de cliente
    Route::post('/cliente/carrito/agregar', [App\Http\Controllers\ClienteController::class, 'agregarAlCarrito'])->name('cliente.carrito.agregar');
    Route::get('/cliente/carrito', [App\Http\Controllers\ClienteController::class, 'mostrarCarrito'])->name('cliente.carrito');
    Route::post('/cliente/carrito/confirmar', [App\Http\Controllers\ClienteController::class, 'confirmarPedido'])->name('cliente.carrito.confirmar');
    Route::post('/cliente/carrito/actualizar', [App\Http\Controllers\ClienteController::class, 'actualizarCantidadCarrito'])->name('cliente.carrito.actualizar');
    Route::delete('/cliente/carrito/{id}', [App\Http\Controllers\ClienteController::class, 'eliminarPlatoCarrito'])->name('cliente.carrito.eliminar');

    // Nueva ruta para vista separada de reseñas
    Route::get('/cliente/reseñas', [App\Http\Controllers\ClienteController::class, 'mostrarResenas'])->name('cliente.reseñas');

    // Rutas para chef
    Route::get('/chef', [App\Http\Controllers\ChefController::class, 'index'])->name('chef.index');
    Route::put('/chef/pedido/{id}', [App\Http\Controllers\ChefController::class, 'updatePedido'])->name('chef.pedido.update');

    Route::post('/chef/pedido/actualizar-agrupado', [App\Http\Controllers\ChefController::class, 'updatePedidoAgrupado'])->name('chef.pedido.actualizar-agrupado');
    Route::get('/chef/pedido/actualizar-agrupado', function () {
        return view('chef.actualizar-agrupado');
    })->name('chef.pedido.actualizar-agrupado.view');

    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/show', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas de funcionalidades repartidor
    Route::get('/repartidor/iniciar-sesion', fn() => view('iniciar-sesion-repartidor'))->name('repartidor.iniciar-sesion');
    Route::get('/repartidor/aceptar-pedido', fn() => view('aceptar-pedido'))->name('repartidor.aceptar-pedido');
    Route::get('/repartidor/recoger-pedido', fn() => view('recoger-pedido'))->name('repartidor.recoger-pedido');
    Route::get('/repartidor/actualizar-estado', fn() => view('actualizar-estado'))->name('repartidor.actualizar-estado');
    Route::get('/repartidor/notificar-entrega', fn() => view('notificar-entrega'))->name('repartidor.notificar-entrega');
    Route::get('/repartidor/resolver-incidencias', fn() => view('resolver-incidencias'))->name('repartidor.resolver-incidencias');

    // Nueva ruta para actualizar estado del pedido por repartidor
    Route::put('/repartidor/pedido/{id}/actualizar-estado', [App\Http\Controllers\RepartidorController::class, 'actualizarEstado'])->name('repartidor.pedido.actualizarEstado');
});

Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/admin/users/create', [AdminController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [AdminController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [AdminController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/admin/platos', [PlatoController::class, 'index'])->name('admin.platos.index');
    Route::get('/admin/platos/create', [PlatoController::class, 'create'])->name('admin.platos.create');
    Route::post('/admin/platos', [PlatoController::class, 'store'])->name('admin.platos.store');
    Route::get('/admin/platos/{id}/edit', [PlatoController::class, 'edit'])->name('admin.platos.edit');
    Route::put('/admin/platos/{id}', [PlatoController::class, 'update'])->name('admin.platos.update');
    Route::delete('/admin/platos/{id}', [PlatoController::class, 'destroy'])->name('admin.platos.destroy');

    // Nueva ruta para detalles de chef
    Route::get('/admin/chef/{id}', [AdminController::class, 'showChefDetails'])->name('admin.chef.details');
});

require __DIR__.'/auth.php';

require __DIR__.'/web_additional.php';
