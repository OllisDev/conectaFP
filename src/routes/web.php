<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('login');
});

Route::get('/register', function () {
    return view('register');
});


Route::get('/feed', function () {
    return view('feed');
});

// -- RUTAS ALUMNO --
Route::get('/ofertas', function () {
    return view('offers');
});

Route::get('/mis-solicitudes', function () {
    return view('myRequests');
});

Route::get('/mi-tutoria', function () {
    return view('myTutorialStudent');
});

// -- RUTAS PROFESOR --
Route::get('/mis-tutorias', function () {
    return view('myTutorialTeacher');
});