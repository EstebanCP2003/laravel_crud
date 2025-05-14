<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Definimos permanentRedirect para que la ruta / redirija a /productos
// Esto es útil para evitar que los usuarios accedan a la ruta raíz
Route::permanentRedirect('/', '/productos')->name(('lista_prodcutos'));

Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
Route::get('/productos/crear', [ProductoController::class, 'create'])->name('productos.crear');
Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
Route::get('/productos/{id}/editar', [ProductoController::class, 'edit'])->name('productos.editar');
Route::put('/productos/{id}', [ProductoController::class, 'update'])->name('productos.actualizar');
Route::delete('/productos/{id}', [ProductoController::class, 'destroy'])->name('productos.eliminar');

