<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profesor;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProfesorController extends Controller
{
    public function listTeacherAPI()
    {
        try {
            $profesores = Profesor::select('id', 'id_usuario', 'id_centro', 'id_grado', 'id_departamento', 'dni')->get();

            if ($profesores) {
                $response = [
                    'response' => 200,
                    'success' => true,
                    'status' => 'ok',
                    'message' => 'Profesor',
                    'profesores' => $profesores
                ];
                return response()->json($response, 200);
            } else {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No existe ningún profesor.'
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

    public function listTeacherByIdAPI($id)
    {
        try {
            $profesor = Profesor::select('id', 'id_usuario', 'id_centro', 'id_grado', 'id_departamento', 'dni')->where('id', $id)->first();

            if (!$profesor) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'El profesor no existe.'
                ];
                return response()->json($response, 404);
            } else {
                $response = [
                    'response' => 200,
                    'success' => true,
                    'status' => 'ok',
                    'profesor' => $profesor
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

    public function registerTeacherAPI(Request $request)
    {
        try {
            $data = $request->validate([
                'nombre' => 'required|string|max:50',
                'apellidos' => 'required|string|max:100',
                'contrasena' => 'required|string|min:8|max:255',
                'email' => 'required|email|max:100|unique:usuario,email',
                'telefono' => 'required|string|regex:/^[6-9][0-9]{8}$/',
                'id_centro' => 'required|integer|exists:centro_educativo,id',
                'id_grado' => 'required|integer|exists:grado,id',
                'id_departamento' => 'required|integer|exists:departamento,id',
                'dni' => 'required|string|spanish_personal_id'
            ], [
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.max' => 'El nombre no puede superar los 50 caracteres.',
                'apellidos.required' => 'Los apellidos son obligatorios.',
                'apellidos.max' => 'Los apellidos no pueden superar los 100 caracteres.',
                'contrasena.required' => 'La contraseña es obligatoria.',
                'contrasena.min' => 'La contraseña debe tener al menos 8 caracteres.',
                'email.required' => 'El email es obligatorio.',
                'email.email' => 'El formato del email no es válido.',
                'email.unique' => 'Este email ya está registrado.',
                'telefono.required' => 'El teléfono es obligatorio.',
                'telefono.regex' => 'El teléfono no es válido.',
                'id_centro.required' => 'El centro educativo es obligatorio.',
                'id_centro.exists' => 'El centro educativo no existe.',
                'id_grado.required' => 'El grado es obligatorio.',
                'id_grado.exists' => 'El grado no existe.',
                'dni.required' => 'El DNI es obligatorio.',
                'dni.spanish_personal_id' => 'DNI incorrecto.'
            ]);

            $response = DB::transaction(function () use ($data) {
                $usuario = Usuario::create([
                    'nombre' => $data['nombre'],
                    'apellidos' => $data['apellidos'],
                    'contrasena' => Hash::make($data['contrasena']),
                    'email' => $data['email'],
                    'telefono' => $data['telefono'],
                    'api_token' => Str::random(60),
                ]);

                Profesor::create([
                    'id_usuario' => $usuario->id,
                    'id_centro' => $data['id_centro'],
                    'id_grado' => $data['id_grado'],
                    'id_departamento' => $data['id_departamento'],
                    'dni' => $data['dni']
                ]);

                return response()->json([
                    'response' => 201,
                    'success' => true,
                    'status' => 'ok',
                    'message' => 'Cuenta de profesor creada correctamente.'
                ]);
            });

            return $response;

        } catch (\Illuminate\Validation\ValidationException $e) {
            $response = [
                'response' => 400,
                'success' => false,
                'status' => 'error',
                'message' => $e->errors()
            ];
            return response()->json($response, 400);
        }
    }
}
