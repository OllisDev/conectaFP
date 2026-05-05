<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Empresa;
use App\Models\Profesor;
use App\Models\Tutoria;
use App\Notifications\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TutoriaController extends Controller
{
    // listar tutorias asginadas al alumno logueado
    public function listTutorialByStudentAPI()
    {
        try {
            $user = Auth::user();

            if (!$user || !$user->alumno) {
                return response()->json([
                    'response' => 401,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No autenticado o el usuario no es un alumno.'
                ], 401);
            }

            $idAlumno = $user->alumno->id;

            $tutorias = Tutoria::with(['profesor.usuario', 'empresa.usuario'])
                ->select('id', 'id_alumno', 'id_profesor', 'id_empresa', 'fecha_inicio', 'fecha_fin', 'estado')
                ->where('id_alumno', $idAlumno)
                ->get();

            if ($tutorias->isEmpty()) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No existe ninguna tutoria de ese alumno.'
                ];
                return response()->json($response, 404);
            } else {
                $response = [
                    'response' => 200,
                    'success' => true,
                    'status' => 'ok',
                    'message' => 'Alumno',
                    'Tutorias' => $tutorias
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

    // listar tutorias del profesor logueado
    public function listTutorialByTeacherAPI()
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
            $tutorias = Tutoria::with(['alumno.usuario', 'empresa.usuario'])
                ->select('id', 'id_alumno', 'id_profesor', 'id_empresa', 'fecha_inicio', 'fecha_fin', 'estado')
                ->where('id_profesor', $idProfesor)
                ->get();

            if ($tutorias->isEmpty()) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No existe ninguna tutoria que gestione ese profesor.'
                ];
                return response()->json($response, 404);

            } else {
                $response = [
                    'response' => 200,
                    'success' => true,
                    'status' => 'ok',
                    'message' => 'Alumno',
                    'Tutorias' => $tutorias
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

    // crear tutoria por el profesor logueado
    public function createTutorialAPI(Request $request)
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

            $data = $request->validate([
                'id_alumno' => 'required|integer|min:1|exists:alumno,id',
                'id_empresa' => 'required|integer|min:1|exists:empresa,id',
                'fecha_inicio' => 'required|date|date_format:Y-m-d H:i:s|after_or_equal:today',
                'fecha_fin' => 'nullable|date|date_format:Y-m-d H:i:s|after:fecha_inicio',
                'estado' => 'required|in:Activa,Finalizada,Cancelada',
            ], [
                'id_alumno.required' => 'El alumno es obligatorio.',
                'id_alumno.integer' => 'El identificador del alumno debe ser un número entero.',
                'id_alumno.min' => 'El identificador del alumno debe ser mayor que 0.',
                'id_alumno.exists' => 'El alumno no existe.',
                'id_empresa.required' => 'La empresa es obligatoria.',
                'id_empresa.integer' => 'El identificador de la empresa debe ser un número entero.',
                'id_empresa.min' => 'El identificador de la empresa debe ser mayor que 0.',
                'id_empresa.exists' => 'La empresa no existe.',
                'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
                'fecha_inicio.date' => 'La fecha de inicio no es válida.',
                'fecha_inicio.date_format' => 'La fecha de inicio debe tener el formato AAAA-MM-DD HH:MM:SS.',
                'fecha_inicio.after_or_equal' => 'La fecha de inicio no puede ser anterior a hoy.',
                'fecha_fin.date' => 'La fecha de fin no es válida.',
                'fecha_fin.date_format' => 'La fecha de fin debe tener el formato AAAA-MM-DD.',
                'fecha_fin.after' => 'La fecha de fin debe tener el formato AAAA-MM-DD HH:MM:SS.',
                'estado.required' => 'El estado es obligatorio.',
                'estado.in' => 'El estado debe ser "Activa", "Finalizada" o "Cancelada".',
            ]);


            $data['id_profesor'] = $idProfesor;

            $tutoria = Tutoria::create($data);

            if ($tutoria) {
                $alumno = Alumno::find($data['id_alumno']);

                if ($alumno && $alumno->usuario) {
                    $alumno->usuario->notify(new Notificacion(
                        'Se te ha asignado una nueva tutoría.',
                        ['tutoria_id' => $tutoria->id]
                    ));
                }

                $response = [
                    'response' => 201,
                    'success' => true,
                    'status' => 'ok',
                    'message' => 'Se ha creado la tutoría correctamente.'
                ];
                return response()->json($response, 201);
            } else {
                $response = [
                    'response' => 500,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No se pudo crear la tutoría.'
                ];
                return response()->json($response, 500);
            }

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

    // actualizar tutoria por el profesor logueado
    public function updateTutorialAPI(Request $request, $id)
    {
        try {
            $tutoria = Tutoria::find($id);

            if (!$tutoria) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'La tutoría no existe.'
                ];
                return response()->json($response, 404);
            }

            $data = $request->validate([
                'fecha_inicio' => 'date|date_format:Y-m-d H:i:s|after_or_equal:today',
                'fecha_fin' => 'date|date_format:Y-m-d H:i:s|after:fecha_inicio',
                'estado' => 'in:Activa,Finalizada,Cancelada'
            ], [
                'fecha_inicio.date' => 'La fecha de inicio no es válida.',
                'fecha_inicio.date_format' => 'La fecha de inicio debe tener el formato AAAA-MM-DD HH:MM:SS.',
                'fecha_inicio.after_or_equal' => 'La fecha de inicio no puede ser anterior a hoy.',
                'fecha_fin.date' => 'La fecha de fin no es válida.',
                'fecha_fin.date_format' => 'La fecha de fin debe tener el formato AAAA-MM-DD.',
                'fecha_fin.after' => 'La fecha de fin debe tener el formato AAAA-MM-DD HH:MM:SS.',
                'estado.in' => 'El estado debe ser "Activa", "Finalizada" o "Cancelada".'
            ]);

            $tutoria->update($data);

            $alumno = $tutoria->alumno;

            if ($alumno && $alumno->usuario) {
                $alumno->usuario->notify(new Notificacion(
                    'Una tutoría ha sido actualizada.',
                    ['tutoria_id' => $tutoria->id]
                ));
            }

            $response = [
                'response' => 200,
                'success' => true,
                'status' => 'ok',
                'message' => 'La tutoria se ha actualizado correctamente.'
            ];
            return response($response, 200);


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

    // eliminar tutoria por el profesor
    public function deleteTutorialAPI($id)
    {
        try {
            $tutoria = Tutoria::where('id', $id)->first();

            if (!$tutoria) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No existe la tutoría.'
                ];
                return response()->json($response, 404);
            }

            $alumno = $tutoria->alumno;

            $tutoria->delete();

            if ($alumno && $alumno->usuario) {
                $alumno->usuario->notify(new Notificacion(
                    'Una tutoría ha sido eliminada.',
                    ['tutoria_id' => $tutoria->id]
                ));
            }

            $response = [
                'response' => 200,
                'success' => true,
                'status' => 'ok',
                'message' => 'La tutoria ha sido eliminado correctamente.'
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
}
