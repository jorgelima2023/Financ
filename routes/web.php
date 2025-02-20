<?php

use App\Http\Controllers\Categoriascontroller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MovimentoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Login 
Route::get('/', [LoginController::class, 'index'])->name('login.index');
Route::post('/login', [LoginController::class, 'loginProcess'])->name('login.process');
Route::get('/logout', [LoginController::class, 'destroy'])->name('login.destroy');

Route::group(['middleware' => 'auth'], function () {

    // Dashboard
    Route::get('/index-dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // ------------------------------ Usuários
    Route::get('/index-user', [UserController::class, 'index'])->name('user.index');
    Route::get('/show-user/{user}', [UserController::class, 'show'])->name('user.show');
    Route::get('/create-user', [UserController::class, 'create'])->name('user.create');
    Route::post('/store-user', [UserController::class, 'store'])->name('user.store');
    Route::get('/edit-user/{user}', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/update-user/{user}', [UserController::class, 'update'])->name('user.update');
    Route::get('/edit-user-password/{user}', [UserController::class, 'editPassword'])->name('user.edit-password');
    Route::put('/update-user-password/{user}', [UserController::class, 'updatePassword'])->name('user.update-password');
    Route::delete('/destroy-user/{user}', [UserController::class, 'destroy'])->name('user.destroy');

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

});