<?php

use App\Http\Controllers\Categoriascontroller;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



// ------------------------------ categorias 
route::get('/index-categoria', 
    [Categoriascontroller::class, 'index'] )
    ->name('categorias.index');
Route::get('/show-categoria', 
    [Categoriascontroller::class, 'show'])
    ->name('categorias.show');
Route::get('/create-categoria', 
    [Categoriascontroller::class, 'create'])
    ->name('categorias.create');
Route::post('/store-categoria', 
    [Categoriascontroller::class, 'store'])
    ->name('categorias.store');
Route::get('/edit-categoria', 
    [Categoriascontroller::class, 'edit'])
    ->name('categorias.edit');
Route::put('/update-categoria', 
    [Categoriascontroller::class, 'update'])
    ->name('categorias.update');
Route::delete('/destroy-categoria', 
    [Categoriascontroller::class, 'destroy'])
    ->name('categorias.destroy');

