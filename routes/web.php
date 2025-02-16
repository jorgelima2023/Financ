<?php

use App\Http\Controllers\Categoriascontroller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MovimentoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/index-dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

// ------------------------------ categorias 
route::get('/index-categoria', 
    [Categoriascontroller::class, 'index'] )->name('categorias.index');

Route::get('/show-categoria/{categoria}', 
    [Categoriascontroller::class, 'show'])->name('categorias.show');

Route::get('/create-categoria', 
    [Categoriascontroller::class, 'create'])->name('categorias.create');

Route::post('/store-categoria', 
    [Categoriascontroller::class, 'store'])->name('categorias.store');

Route::get('/edit-categoria/{categoria}', 
    [Categoriascontroller::class, 'edit'])->name('categorias.edit'); // carrega form

Route::put('/update-categoria/{categoria}', 
    [Categoriascontroller::class, 'update'])->name('categorias.update'); // receber dados form

Route::delete('/destroy-categoria/{categoria}', 
    [Categoriascontroller::class, 'destroy'])->name('categorias.destroy');

// ------------------------------ movimentos
route::get('/index-movimento/{categoria}', 
    [MovimentoController::class, 'index'] )->name('movimentos.index');