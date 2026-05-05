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

    // listar todos los alumnos
    public function listStudentAPI()
    {
        try {
            $alumnos = Alumno::with('usuario')
                ->select('id', 'id_usuario', 'id_profesor', 'id_centro', 'id_grado', 'curso', 'dni', 'cv', 'disponibilidad')
                ->get();

            if ($alumnos->isEmpty()) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No existe ningún alumno.'
                ];
                return response()->json($response, 404);
            } else {
                $response = [
                    'response' => 200,
                    'success' => true,
                    'status' => 'ok',
                    'message' => 'Alumno',
                    'alumnos' => $alumnos
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

    // listar un estudiante por su id
    public function listStudentByIdAPI($id)
    {
        try {
            $alumno = Alumno::select('id', 'id_usuario', 'id_centro', 'id_grado', 'curso', 'dni', 'cv', 'disponibilidad')
                ->where('id', $id)
                ->first();

            if (!is_numeric($id) || (int) $id <= 0) {
                $response = [
                    'response' => 400,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'El ID proporcionado no es válido.'
                ];
                return response()->json($response, 400);
            }

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

    // listar los alumnos asignados por el profesor logueado
    public function listStudentByTeacherAPI()
    {
        try {
            $user = Auth::user();
            if (!$user || !$user->profesor) {
                return response()->json([
                    'response' => 401,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No autenticado o el usuario no es profesor.'
                ], 401);
            }
            $idProfesor = $user->profesor->id;


            $alumnos = Alumno::with(['usuario', 'grado'])
                ->where('id_profesor', $idProfesor)
                ->select('id', 'id_usuario', 'id_centro', 'id_grado', 'curso', 'dni', 'cv', 'disponibilidad')
                ->get();

            if ($alumnos->isEmpty()) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No hay alumnos asignados a este profesor.'
                ];
                return response()->json($response, 404);
            }

            $response = [
                'response' => 200,
                'success' => true,
                'status' => 'ok',
                'alumnos' => $alumnos
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

    // Registro de un alumno
    public function registerStudentAPI(Request $request)
    {
        try {
            $data = $request->validate([
                'nombre' => 'required|string|min:2|max:50|regex:/^[\p{L}\s\-\']+$/u',
                'apellidos' => 'required|string|min:2|max:100|regex:/^[\p{L}\s\-\']+$/u',
                'contrasena' => [
                    'required',
                    'string',
                    'min:8',
                    'max:255',
                    \Illuminate\Validation\Rules\Password::min(8)
                        ->mixedCase()
                        ->numbers()
                        ->symbols()
                ],
                'email' => 'required|email|lowercase|max:100|unique:usuario,email',
                'telefono' => 'required|string|regex:/^[6-9][0-9]{8}$/|unique:usuario,telefono',
                'id_centro' => 'required|integer|min:1|exists:centro_educativo,id',
                'id_profesor' => 'required|integer|min:1|exists:profesor,id',
                'id_grado' => 'required|integer|min:1|exists:grado,id',
                'fecha_nacimiento' => 'required|date|before:' . now()->subYears(16)->toDateString() . '|after:1900-01-01',
                'curso' => 'required|in:1º,2º',
                'dni' => 'required|string|size:9|regex:/^[0-9]{8}[A-Za-z]$/|spanish_personal_id|unique:alumno,dni',
                'cv' => 'required|file|mimes:pdf|mimetypes:application/pdf|max:2048'
            ], [
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.max' => 'El nombre no puede superar los 50 caracteres.',
                'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
                'nombre.regex' => 'El nombre solo puede contener letras, espacios, guiones y apóstrofes.',
                'apellidos.required' => 'Los apellidos son obligatorios.',
                'apellidos.max' => 'Los apellidos no pueden superar los 100 caracteres.',
                'apellidos.min' => 'Los apellidos deben tener al menos 2 caracteres.',
                'apellidos.regex' => 'Los apellidos solo pueden contener letras, espacios, guiones y apóstrofes.',
                'contrasena.required' => 'La contraseña es obligatoria.',
                'contrasena.min' => 'La contraseña debe tener al menos 8 caracteres.',
                'contrasena.max' => 'La contraseña no puede superar los 255 caracteres.',
                'contrasena.password' => 'La contraseña debe contener mayúsculas, minúsculas, números y símbolos.',
                'email.required' => 'El email es obligatorio.',
                'email.email' => 'El formato del email no es válido.',
                'email.unique' => 'Este email ya está registrado con otra cuenta.',
                'email.max' => 'El email no puede superar los 100 caracteres.',
                'email.lowercase' => 'El email debe estar en minúsculas.',
                'telefono.required' => 'El teléfono es obligatorio.',
                'telefono.regex' => 'El teléfono no es válido.',
                'telefono.unique' => 'El teléfono ya está registrado con otra cuenta.',
                'id_centro.required' => 'El centro educativo es obligatorio.',
                'id_centro.exists' => 'El centro educativo no existe.',
                'id_centro.integer' => 'El identificador del centro debe ser un número entero.',
                'id_profesor.required' => 'El profesor es obligatorio.',
                'id_profesor.exists' => 'El profesor no existe.',
                'id_profesor.integer' => 'El identificador del profesor debe ser un número entero.',
                'id_grado.required' => 'El grado es obligatorio.',
                'id_grado.exists' => 'El grado no existe.',
                'id_grado.integer' => 'El identificador del grado debe ser un número entero.',
                'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
                'fecha_nacimiento.date' => 'El formato de la fecha no es válido.',
                'fecha_nacimiento.after' => 'La fecha de nacimeinto no puede ser antes de 1900.',
                'fecha_nacimiento.before' => 'La edad debe ser mayor a 16 años.',
                'curso.required' => 'El curso es obligatorio.',
                'curso.in' => 'El curso debe ser "1º" o "2º".',
                'dni.required' => 'El DNI es obligatorio.',
                'dni.size' => 'El DNI debe tener 9 caractéres.',
                'dni.regex' => 'El formato del DNI no es valido.',
                'dni.spanish_personal_id' => 'DNI incorrecto.',
                'dni.unique' => 'El DNI está registrado con otra cuenta.',
                'cv.required' => 'El CV es obligatorio.',
                'cv.file' => 'El CV debe ser un archivo.',
                'cv.mimes' => 'El CV debe ser un archivo PDF.',
                'cv.mimetypes' => 'El formato de CV no es válido.',
                'cv.max' => 'El CV no puede superar los 2MB.',
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
                    'id_profesor' => $data['id_profesor'],
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
