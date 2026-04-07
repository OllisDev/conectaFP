<?php

use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\CentroEducativoController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\ProfesorController;
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
Route::post('/alumno/register', [AlumnoController::class, 'registerStudentAPI'])->name('studentAPI.registerStudent');
Route::middleware('auth:api')->post('/alumno/crear', [AlumnoController::class, 'createStudentAPI'])->name('studentAPI.createStudent');
Route::middleware('auth:api')->put('/alumno/{alumno}', [AlumnoController::class, 'updateStudentAPI'])->name('studentAPI.updateStudent');
Route::middleware('auth:api')->delete('/alumno/{alumno}', [AlumnoController::class, 'deleteStudentAPI'])->name('studentAPI.deleteStudent');

// rutas para la autenticación de la empresa
Route::get('/empresa', [EmpresaController::class, 'listCompanyAPI'])->name('companyAPI.listCompany');
Route::get('/empresa/{empresa}', [EmpresaController::class, 'listCompanyByIdAPI'])->name('companyAPI.listCompanyById');
Route::post('/empresa/register', [EmpresaController::class, 'registerCompanyAPI'])->name('companyAPI.registerCompany');
Route::middleware('auth:api')->post('/empresa/crear', [EmpresaController::class, 'createCompanyAPI'])->name('companyAPI.createCompany');
Route::middleware('auth:api')->put('/empresa/{empresa}', [EmpresaController::class, 'updateCompanyAPI'])->name('companyAPI.updateCompany');
Route::middleware('auth:api')->delete('/empresa/{empresa}', [EmpresaController::class, 'deleteCompanyAPI'])->name('companyAPI.deleteCompany');

// rutas para la autenticación del profesor
Route::get('/profesor', [ProfesorController::class, 'listTeacherAPI'])->name('teacherAPI.listTeacher');
Route::get('/profesor/{profesor}', [ProfesorController::class, 'listTeacherByIdAPI'])->name('teacherAPI.listTeacherById');
Route::post('/profesor/register', [ProfesorController::class, 'registerTeacherAPI'])->name('teacherAPI.registerTeacher');
Route::middleware('auth:api')->post('/profesor/crear', [ProfesorController::class, 'createTeacherAPI'])->name('teacherAPI.createTeacher');
Route::middleware('auth:api')->put('/profesor/{profesor}', [ProfesorController::class, 'updateTeacherAPI'])->name('teacherAPI.updateTeacher');
Route::middleware('auth:api')->delete('/profesor/{profesor}', [ProfesorController::class, 'deleteTeacherAPI'])->name('teacherAPI.deleteTeacher');

// rutas para los centros educativos
Route::get('/centro', [CentroEducativoController::class, 'listSchoolAPI'])->name('schoolAPI.listSchool');
Route::post('centro/crear', [CentroEducativoController::class, 'createSchoolAPI'])->name('schoolAPI.createSchool');