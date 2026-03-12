<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

Route::get('/login', function () {
    return response()->json([
        'response' => 401,
        'success' => false,
        'status' => 'unauthenticated',
        'message' => 'No autenticado.'
    ], 401);
})->name('login');

// -- RUTAS API CRUD --

// rutas para la autenticación del usuario
Route::middleware('auth:api')->get('/usuario', [UsuarioController::class, 'listUserAPI'])->name('userAPI.listUser');
Route::middleware('auth:api')->get('/usuario/{usuario}', [UsuarioController::class, 'listUserByIdAPI'])->name('userAPI.listUserById');
Route::post('/usuario/crear', [UsuarioController::class, 'createUserAPI'])->name('userAPI.createUser');
Route::middleware('auth:api')->put('/usuario/{usuario}', [UsuarioController::class, 'updateUserAPI'])->name('userAPI.updateUser');
Route::middleware('auth:api')->delete('/usuario/{usuario}/eliminar', [UsuarioController::class, 'deleteUserAPI'])->name('userAPI.deleteUser');
