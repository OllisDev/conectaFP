<?php

use App\Http\Controllers\AlumnoController;
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
Route::get('/usuario', [UsuarioController::class, 'listUserAPI'])->name('userAPI.listUser');
Route::get('/usuario/{usuario}', [UsuarioController::class, 'listUserByIdAPI'])->name('userAPI.listUserById');
Route::post('/usuario/crear', [UsuarioController::class, 'createUserAPI'])->name('userAPI.createUser');
Route::put('/usuario/{usuario}', [UsuarioController::class, 'updateUserAPI'])->name('userAPI.updateUser');
Route::delete('/usuario/{usuario}/eliminar', [UsuarioController::class, 'deleteUserAPI'])->name('userAPI.deleteUser');

// rutas para la autenticación del alumno
Route::get('/alumno', [AlumnoController::class, 'listStudentAPI'])->name('studentAPI.listStudent');
Route::get('/alumno/{alumno}', [AlumnoController::class, 'listStudentByIdAPI'])->name('studentAPI.listStudent');
Route::middleware('auth:api')->post('/alumno/crear', [AlumnoController::class, 'createStudentAPI'])->name('studentAPI.createStudent');
Route::middleware('auth:api')->put('/alumno/{alumno}', [AlumnoController::class, 'updateStudentAPI'])->name('studentAPI.updateStudent');
Route::middleware('auth:api')->delete('/alumno/{alumno}', [AlumnoController::class, 'deleteStudentAPI'])->name('studentAPI.deleteStudent');

