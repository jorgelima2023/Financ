<?php

use App\Http\Controllers\Categoriascontroller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MovimentoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
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
    Route::get('/index-user', [UserController::class, 'index'])->name('user.index')->middleware('permission:index-user');
    Route::get('/show-user/{user}', [UserController::class, 'show'])->name('user.show')->middleware('permission:show-user');
    Route::get('/create-user', [UserController::class, 'create'])->name('user.create')->middleware('permission:create-user');
    Route::post('/store-user', [UserController::class, 'store'])->name('user.store')->middleware('permission:create-user');
    Route::get('/edit-user/{user}', [UserController::class, 'edit'])->name('user.edit')->middleware('permission:edit-user');
    Route::put('/update-user/{user}', [UserController::class, 'update'])->name('user.update')->middleware('permission:edit-user');
    Route::get('/edit-user-password/{user}', [UserController::class, 'editPassword'])->name('user.edit-password')->middleware('permission:edit-user-password');
    Route::put('/update-user-password/{user}', [UserController::class, 'updatePassword'])->name('user.update-password')->middleware('permission:edit-user-password');
    Route::delete('/destroy-user/{user}', [UserController::class, 'destroy'])->name('user.destroy')->middleware('permission:destroy-user');

    Route::get('/generate-pdf-user', [UserController::class, 'generatePdf'])
    ->name('user.generate-pdf');
    
    Route::get('/generate-csv-user', [UserController::class, 'generateCsv'])
    ->name('user.generate-Csv');
    
    // ------------------------------ categorias 
    route::get('/index-categoria', 
        [Categoriascontroller::class, 'index'] )->name('categorias.index')
        ->middleware('permission:index-categoria');

    Route::get('/show-categoria/{categoria}', 
        [Categoriascontroller::class, 'show'])->name('categorias.show')
        ->middleware('permission:show-categoria');

    Route::get('/create-categoria', 
        [Categoriascontroller::class, 'create'])->name('categorias.create')
        ->middleware('permission:create-categoria');

    Route::post('/store-categoria', 
        [Categoriascontroller::class, 'store'])->name('categorias.store')
        ->middleware('permission:create-categoria');

    Route::get('/edit-categoria/{categoria}', 
        [Categoriascontroller::class, 'edit'])->name('categorias.edit')
        ->middleware('permission:edit-categoria'); // carrega form

    Route::put('/update-categoria/{categoria}', 
        [Categoriascontroller::class, 'update'])->name('categorias.update')
        ->middleware('permission:edit-categoria'); // receber dados form

    Route::delete('/destroy-categoria/{categoria}', 
        [Categoriascontroller::class, 'destroy'])->name('categorias.destroy')
        ->middleware('permission:destroy-categoria');

    // ------------------------------ movimentos
    route::get('/index-movimento/{categoria}', 
        [MovimentoController::class, 'index'] )->name('movimentos.index');

    // Papéis
    Route::get('/index-role', [RoleController::class, 'index'])->name('role.index')
    ->middleware('permission:index-role'); 
    
    Route::get('/create-role', [RoleController::class, 'create'])->name('role.create')
    ->middleware('permission:create-role'); 
    
    Route::post('/store-role', [RoleController::class, 'store'])->name('role.store')
    ->middleware('permission:create-role'); 
    
    Route::get('/edit-role/{role}', [RoleController::class, 'edit'])->name('role.edit')
    ->middleware('permission:edit-role'); 
    
    Route::put('/update-role/{role}', [RoleController::class, 'update'])->name('role.update')
    ->middleware('permission:edit-role'); 
    
    Route::delete('/destroy-role/{role}', [RoleController::class, 'destroy'])->name('role.destroy')
    ->middleware('permission:destroy-role'); 

    // Permissão do papel
    Route::get('/index-role-permission/{role}', [RolePermissionController::class, 'index'])
    ->name('role-permission.index')
    ->middleware('permission:index-role-permission');

    // update
    Route::get('/update-role-permission/{role}', [RolePermissionController::class, 'update'])
    ->name('role-permission.update')
    ->middleware('permission:update-role-permission'); 
});