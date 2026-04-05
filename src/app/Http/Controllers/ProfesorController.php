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
            $profesores = Profesor::select('id', 'id_usuario', 'departamento', 'telefono')->get();

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
            $profesor = Profesor::select('id', 'id_usuario', 'departamento', 'telefono')->where('id', $id)->first();

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

    public function createTeacherAPI(Request $request)
    {
        try {
            $data = $request->validate([
                'departamento' => 'required|string|max:100',
                'telefono' => 'required|string|max:20',
            ]);

            $data['id_usuario'] = Auth::id();
            $profesor = Profesor::create($data);

            if ($profesor) {
                $response = [
                    'response' => 201,
                    'success' => true,
                    'status' => 'ok',
                    'message' => 'Se ha creado el profesor correctamente.'
                ];
                return response()->json($response, 201);
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

    public function updateTeacherAPI(Request $request, $id)
    {
        try {
            $profesor = Profesor::find($id);

            if (!$profesor) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'El profesor no existe.'
                ];
                return response()->json($response, 404);
            }

            $data = $request->validate([
                'departamento' => 'required|string|max:100',
                'telefono' => 'required|string|max:20',
            ]);

            $data['id_usuario'] = Auth::id();
            $profesor->update($data);

            $response = [
                'response' => 200,
                'success' => true,
                'status' => 'ok',
                'message' => 'El profesor se ha actualizado correctamente.'
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

    public function deleteTeacherAPI($id)
    {
        try {
            $profesor = Profesor::where('id', $id)->where('id_usuario', Auth::id())->first();

            if (!$profesor) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No existe el profesor.'
                ];
                return response()->json($response, 404);
            }

            $profesor->delete();

            $response = [
                'response' => 200,
                'success' => true,
                'status' => 'ok',
                'message' => 'El profesor ha sido eliminado correctamente.'
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

    public function registerTeacherAPI(Request $request)
    {
        try {
            $data = $request->validate([
                'nombre' => 'required|string|max:50',
                'apellidos' => 'required|string|max:100',
                'contrasena' => 'required|string|min:8|max:255',
                'email' => 'required|email|max:100|unique:usuario,email',
                'fecha_nacimiento' => 'required|date|before:today',
                'departamento' => 'required|string|max:100',
                'telefono' => 'required|digits:9',
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
                'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
                'fecha_nacimiento.date' => 'El formato de la fecha no es válido.',
                'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
                'telefono.digits' => 'El número de teléfono debe tener 9 números.',
            ]);

            $response = DB::transaction(function () use ($data) {
                $usuario = Usuario::create([
                    'nombre' => $data['nombre'],
                    'apellidos' => $data['apellidos'],
                    'contrasena' => Hash::make($data['contrasena']),
                    'email' => $data['email'],
                    'fecha_nacimiento' => $data['fecha_nacimiento'],
                    'api_token' => Str::random(60),
                ]);

                Profesor::create([
                    'id_usuario' => $usuario->id,
                    'departamento' => $data['departamento'],
                    'telefono' => $data['telefono']
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
