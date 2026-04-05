<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alumno;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AlumnoController extends Controller
{
    public function listStudentAPI()
    {
        try {
            $alumnos = Alumno::select('id', 'id_usuario', 'grado', 'curso', 'cv_url', 'disponibilidad', 'eliminado')->get();

            if ($alumnos) {
                $response = [
                    'response' => 200,
                    'success' => true,
                    'status' => 'ok',
                    'message' => 'Alumno',
                    'alumnos' => $alumnos
                ];
                return response()->json($response, 200);
            } else {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No existe ningún alumno.'
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

    public function listStudentByIdAPI($id)
    {
        try {
            $alumno = Alumno::select('id', 'id_usuario', 'grado', 'curso', 'cv_url', 'disponibilidad', 'eliminado')->where('id', $id)->first();

            if (!$alumno) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'El alumno no existe.'
                ];
                return response()->json($response, 404);
            } else {
                $response = [
                    'response' => 200,
                    'success' => true,
                    'status' => 'ok',
                    'alumno' => $alumno
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

    public function createStudentAPI(Request $request)
    {
        try {
            $data = $request->validate([
                'grado' => 'required|string|max:100',
                'curso' => 'required|string|max:20',
                'cv_url' => 'required|string|max:255',
                'disponibilidad' => 'required|boolean'
            ]);

            $data['id_usuario'] = Auth::id();
            $alumno = Alumno::create($data);

            if ($alumno) {
                $response = [
                    'response' => 201,
                    'success' => true,
                    'status' => 'ok',
                    'message' => 'Se ha creado el alumno correctamente.'
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

    public function updateStudentAPI(Request $request, $id)
    {
        try {
            $student = Alumno::find($id);

            if (!$student) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'El alumno no existe.'
                ];
                return response()->json($response, 404);
            }

            $data = $request->validate([
                'grado' => 'required|string|max:100',
                'curso' => 'required|string|max:20',
                'cv_url' => 'required|string|max:255',
                'disponibilidad' => 'required|boolean'
            ]);

            $data['id_usuario'] = Auth::id();
            $student->update($data);

            $response = [
                'response' => 200,
                'success' => true,
                'status' => 'ok',
                'message' => 'El alumno se ha actualizado correctamente.'
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

    public function deleteStudentAPI($id)
    {
        try {
            $student = Alumno::where('id', $id)->where('id_usuario', Auth::id())->first();

            if (!$student) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No existe el alumno.'
                ];
                return response()->json($response, 404);
            }

            $student->delete();

            $response = [
                'response' => 200,
                'success' => true,
                'status' => 'ok',
                'message' => 'El alumno ha sido eliminado correctamente.'
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

    public function registerStudentAPI(Request $request)
    {
        try {
            $data = $request->validate([
                'nombre' => 'required|string|max:50',
                'apellidos' => 'required|string|max:100',
                'contrasena' => 'required|string|min:8|max:255',
                'email' => 'required|email|max:100|unique',
                'fecha_nacimiento' => 'required|date|before:today',
                'grado' => 'required|string|max:100',
                'curso' => 'required|string|max:20',
                'cv_url' => 'required|string|max:255',
                'disponibilidad' => 'required|boolean',
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
                'grado.required' => 'El grado es obligatorio.',
                'curso.required' => 'El curso es obligatorio.',
                'cv_url.required' => 'El CV es obligatorio.',
                'disponibilidad.required' => 'La disponibilidad es obligatoria.',
                'disponibilidad.boolean' => 'El valor de disponibilidad no es válido.'
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

                Alumno::create([
                    'id_usuario' => $usuario->id,
                    'grado' => $data['grado'],
                    'curso' => $data['curso'],
                    'cv_url' => $data['cv_url'],
                    'disponibilidad' => $data['disponibilidad'],
                ]);

                return response()->json([
                    'response' => 201,
                    'success' => true,
                    'status' => 'ok',
                    'message' => 'Cuenta de alumno creada correctamente.'
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
