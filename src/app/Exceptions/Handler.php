<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    // ... otros métodos y propiedades

    protected function unauthenticated($request, \Illuminate\Auth\AuthenticationException $exception)
    {
        return $request->expectsJson()
            ? response()->json([
                'response' => 401,
                'success' => false,
                'status' => 'unauthenticated',
                'message' => 'No autenticado.'
            ], 401)
            : redirect()->guest(route('login'));
    }
}