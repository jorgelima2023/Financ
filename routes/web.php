<?php

use App\Http\Controllers\Categoriascontroller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MovimentoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Login 
Route::get('/', [LoginController::class, 'index'])->name('login.index');
Route::post('/login', [LoginController::class, 'loginProcess'])->name('login.process');
Route::get('/logout', [LoginController::class, 'destroy'])->name('login.destroy');

// Recuperar senha
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPassword'])->name('forgot-password.show');
Route::post('/forgot-password', [ForgotPasswordController::class, 'submitForgotPassword'])->name('forgot-password.submit');

Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetPassword'])->name('password.reset');

Route::post('/reset-password', [ForgotPasswordController::class, 'submitResetPassword'])->name('reset-password.submit');

Route::group(['middleware' => 'auth'], function () {

    // Dashboard
    Route::get('/index-dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Perfil
    Route::get('/show-profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/edit-profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/update-profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/edit-profile-password', [ProfileController::class, 'editPassword'])->name('profile.edit-password');
    Route::put('/update-profile-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    
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