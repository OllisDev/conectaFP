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
            $alumnos = Alumno::select('id', 'id_usuario', 'id_centro', 'id_grado', 'curso', 'dni', 'cv', 'disponibilidad')->get();

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
            $alumno = Alumno::select('id', 'id_usuario', 'id_centro', 'id_grado', 'curso', 'dni', 'cv', 'disponibilidad')->where('id', $id)->first();

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

    public function registerStudentAPI(Request $request)
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
                'fecha_nacimiento' => 'required|date|before:today',
                'curso' => 'required|in:1º,2º',
                'dni' => 'required|string|spanish_personal_id',
                'cv' => 'required|file|mimes:pdf|max:2048'
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
                'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
                'fecha_nacimiento.date' => 'El formato de la fecha no es válido.',
                'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
                'curso.required' => 'El curso es obligatorio.',
                'curso.in' => 'El curso debe ser "1º" o "2º".',
                'dni.required' => 'El DNI es obligatorio.',
                'dni.spanish_personal_id' => 'DNI incorrecto.',
                'cv.required' => 'El CV es obligatorio.'
            ]);

            $response = DB::transaction(function () use ($data, $request) {
                $usuario = Usuario::create([
                    'nombre' => $data['nombre'],
                    'apellidos' => $data['apellidos'],
                    'contrasena' => Hash::make($data['contrasena']),
                    'email' => $data['email'],
                    'telefono' => $data['telefono'],
                    'api_token' => Str::random(60),
                ]);

                $cvPath = $request->file('cv')->store('cv', 'public');


                if (!$cvPath) {
                    throw new \Exception('Error al guardar el CV.');
                }

                Alumno::create([
                    'id_usuario' => $usuario->id,
                    'id_centro' => $data['id_centro'],
                    'id_grado' => $data['id_grado'],
                    'fecha_nacimiento' => $data['fecha_nacimiento'],
                    'curso' => $data['curso'],
                    'dni' => $data['dni'],
                    'cv' => $cvPath
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
