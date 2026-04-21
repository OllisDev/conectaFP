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

Route::get('/mis-solicitudes', function () {
    return view('myRequests');
});