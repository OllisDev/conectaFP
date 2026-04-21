<?php

use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\CentroEducativoController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\GradoController;
use App\Http\Controllers\OfertaController;
use App\Http\Controllers\ProfesorController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\SolicitudController;
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
Route::post('/usuario/login', [UsuarioController::class, 'loginUserAPI'])->name('userAPI.loginUser');
Route::put('/usuario/{usuario}', [UsuarioController::class, 'updateUserAPI'])->name('userAPI.updateUser');
Route::delete('/usuario/{usuario}/eliminar', [UsuarioController::class, 'deleteUserAPI'])->name('userAPI.deleteUser');

// rutas para la autenticación del alumno
Route::get('/alumno', [AlumnoController::class, 'listStudentAPI'])->name('studentAPI.listStudent');
Route::get('/alumno/{alumno}', [AlumnoController::class, 'listStudentByIdAPI'])->name('studentAPI.listStudent');
Route::post('/alumno/register', [AlumnoController::class, 'registerStudentAPI'])->name('studentAPI.registerStudent');

// rutas para la autenticación de la empresa
Route::get('/empresa', [EmpresaController::class, 'listCompanyAPI'])->name('companyAPI.listCompany');
Route::get('/empresa/{empresa}', [EmpresaController::class, 'listCompanyByIdAPI'])->name('companyAPI.listCompanyById');
Route::post('/empresa/register', [EmpresaController::class, 'registerCompanyAPI'])->name('companyAPI.registerCompany');

// rutas para los sectores de la empresa
Route::get('/sector', [SectorController::class, 'listSectorAPI'])->name('sectorAPI.listSector');
Route::post('/sector/crear', [SectorController::class, 'createSectorAPI'])->name('sectorAPI.createSector');

// rutas para la autenticación del profesor
Route::get('/profesor', [ProfesorController::class, 'listTeacherAPI'])->name('teacherAPI.listTeacher');
Route::get('/profesor/{profesor}', [ProfesorController::class, 'listTeacherByIdAPI'])->name('teacherAPI.listTeacherById');
Route::post('/profesor/register', [ProfesorController::class, 'registerTeacherAPI'])->name('teacherAPI.registerTeacher');

// rutas para los departamentos de cada profesor
Route::get('/departamento', [DepartamentoController::class, 'listDepartmentAPI'])->name('listDepartment');
Route::post('/departamento/crear', [DepartamentoController::class, 'createDepartmentAPI'])->name('createDepartment');

// rutas para los centros educativos
Route::get('/centro', [CentroEducativoController::class, 'listSchoolAPI'])->name('schoolAPI.listSchool');
Route::post('centro/crear', [CentroEducativoController::class, 'createSchoolAPI'])->name('schoolAPI.createSchool');

// rutas para los grados
Route::get('grado', [GradoController::class, 'listDegreeAPI'])->name('degreeAPI.listDegree');
Route::post('grado/crear', [GradoController::class, 'createDegreeAPI'])->name('degreeAPI.createDegree');

// rutas para los ofertas de trabajo
Route::get('/oferta', [OfertaController::class, 'listOfferAPI'])->name('offerAPI.listOffer');
Route::get('/oferta/{oferta}', [OfertaController::class, 'listOfferByIdAPI'])->name('offerAPI.listOfferById');
Route::post('oferta/crear', [OfertaController::class, 'createOfferAPI'])->name('offerAPI.createOffer');

// rutas para las solicitudes
Route::get('/solicitud/alumno/{id_alumno}', [SolicitudController::class, 'listRequestByStudentAPI'])->name('requestAPI.listRequestByStudent');
Route::post('/solicitud', [SolicitudController::class, 'requestAPI'])->name('requestAPI.request');
Route::delete('/solicitud/{solicitud}', [SolicitudController::class, 'deleteRequestAPI'])->name('requestAPI.deleteRequest');