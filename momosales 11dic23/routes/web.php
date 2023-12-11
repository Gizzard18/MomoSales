<?php

use App\Http\Livewire\Citas;
use App\Http\Livewire\Marcas;
use App\Http\Livewire\Ventas;
use App\Http\Livewire\Ajustes;
use App\Http\Livewire\Clientes;
use App\Http\Livewire\Empleados;
use App\Http\Livewire\Productos;
use App\Http\Livewire\Servicios;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;
use App\Http\Livewire\CategoriaClientes;
use App\Http\Livewire\CategoriaProductos;
use App\Http\Livewire\CategoriaServicios;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('CategoriaClientes', CategoriaClientes::class)->name('CategoriaClientes');
Route::get('CategoriaServicios', CategoriaServicios::class)->name('CategoriaServicios');
Route::get('CategoriaProductos', CategoriaProductos::class)->name('CategoriaProductos');
Route::get('Empleados', Empleados::class)->name('Empleados');
Route::get('marcas', Marcas::class)->name('marcas');
Route::get('Ventas', Ventas::class)->name('Ventas');
Route::get('productos', Productos::class)->name('productos');
Route::get('Servicios', Servicios::class)->name('Servicios');
Route::get('Ajustes', Ajustes::class)->name('Ajustes');
Route::get('clientes', Clientes::class)->name('clientes');
Route::get('Citas', Citas::class)->name('Citas');

Route::post('Citas', [Citas::class,'store'])->name('citas.citas.store');

Route::get('data/categoriesS',[DataController::class, 'getCategoriesS'])->name('data.categoriesS');
Route::get('data/categories',[DataController::class, 'getCategories'])->name('data.categories');
Route::get('data/customers',[DataController::class, 'getCustomers'])->name('data.customers');

require __DIR__.'/auth.php';
