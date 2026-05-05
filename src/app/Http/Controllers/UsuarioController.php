<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsuarioController extends Controller
{

    // listar notificaciones de cada uno de los usuarios
    public function listNotificationAPi(Request $request)
    {
        try {
            $response = [
                'response' => 200,
                'success' => true,
                'message' => 'notificaciones',
                'notificaciones' => $request->user()->notifications
            ];
            return response()->json($response, 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            $response = [
                'response' => 422,
                'success' => false,
                'status' => 'error',
                'message' => 'Error de validación: ' . $e->getMessage()
            ];
            return response()->json($response, 422);
        }
    }

    // listar todos los usuarios
    public function listUserAPI()
    {
        try {
            $usuarios = Usuario::select('id', 'nombre', 'apellidos', 'contrasena', 'email', 'activo', 'fecha_registro')->get();

            if ($usuarios) {
                $response = [
                    'response' => 200,
                    'success' => true,
                    'status' => 'ok',
                    'message' => 'Usuario',
                    'usuarios' => $usuarios
                ];
                return response()->json($response, 200);
            } else {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No existe ningún usuario.'
                ];
                return response()->json($response, 404);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            $response = [
                'response' => 422,
                'success' => false,
                'status' => 'error',
                'message' => 'Error de validación: ' . $e->getMessage()
            ];
            return response()->json($response, 422);
        }

    }

    // listar un usuario por id
    public function listUserByIdAPI($id)
    {
        try {
            $usuario = Usuario::select('id', 'nombre', 'apellidos', 'contrasena', 'email', 'activo', 'fecha_registro')
                ->where('id', $id)
                ->first();

            if (!$usuario) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'El usuario no existe.'
                ];
                return response()->json($response, 404);
            } else {
                $response = [
                    'response' => 200,
                    'success' => true,
                    'status' => 'ok',
                    'usuario' => $usuario
                ];
                return response()->json($response, 200);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            $response = [
                'response' => 422,
                'success' => false,
                'status' => 'error',
                'message' => 'Error de validación: ' . $e->getMessage()
            ];
            return response()->json($response, 422);
        }
    }

    // login del usuario
    public function loginUserAPI(Request $request)
    {
        try {
            $data = $request->validate([
                'email' => 'required|email|lowercase|max:100',
                'contrasena' => 'required|string|min:8|max:255',
            ], [
                'email.required' => 'El email es obligatorio.',
                'email.email' => 'El formato del email no es válido.',
                'email.lowercase' => 'El email debe estar en minúsculas.',
                'contrasena.required' => 'La contraseña es obligatoria.',
                'contrasena.min' => 'La contraseña debe tener al menos 8 caracteres.',
                'contrasena.max' => 'La contraseña debe tener menos de 255 caracteres.'
            ]);

            $usuario = Usuario::where('email', $data['email'])->first();

            if (!$usuario || !Hash::check($data['contrasena'], $usuario->contrasena)) {
                $response = [
                    'response' => 401,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'Credenciales incorrectas.'
                ];
                return response()->json($response, 401);
            }

            if (!$usuario->activo) {
                $response = [
                    'response' => 403,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'La cuenta está desactivada.'
                ];
                return response()->json($response, 403);
            }

            $rol = null;
            $idRol = null;
            if ($usuario->alumno()->exists()) {
                $rol = 'alumno';
                $idRol = $usuario->alumno->id;
            } elseif ($usuario->profesor()->exists()) {
                $rol = 'profesor';
                $idRol = $usuario->profesor->id;
            } elseif ($usuario->empresa()->exists()) {
                $rol = 'empresa';
                $idRol = $usuario->empresa->id;
            }

            $response = [
                'response' => 200,
                'success' => true,
                'status' => 'ok',
                'message' => 'Inicio de sesión correcto.',
                'api_token' => $usuario->api_token,
                'nombre' => $usuario->nombre,
                'id_rol' => $idRol,
                'rol' => $rol
            ];
            return response()->json($response, 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            $response = [
                'response' => 400,
                'success' => false,
                'status' => 'error',
                'message' => $e->errors()
            ];
            return response()->json($response, 422);
        }
    }

    // cerrar sesión
    public function logoutUserAPI(Request $request)
    {
        try {
            $token = $request->bearerToken();
            if (!$token) {
                return response()->json([
                    'response' => 401,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'Token no proporcionado.'
                ], 401);
            }

            $usuario = Usuario::where('api_token', $token)->first();

            if (!$usuario) {
                return response()->json([
                    'response' => 401,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'Usuario no autenticado.'
                ], 401);
            }

            return response()->json([
                'response' => 200,
                'success' => true,
                'status' => 'ok',
                'message' => 'Sesión cerrada correctamente.'
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'response' => 422,
                'success' => false,
                'status' => 'error',
                'message' => 'Error de validación: ' . $e->getMessage()
            ], 422);
        }
    }
}
