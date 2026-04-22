<?php

namespace App\Http\Controllers;

use App\Models\Tutoria;
use App\Models\Valoracion;
use Illuminate\Http\Request;

class TutoriaController extends Controller
{
    public function listTutorialByStudentAPI($id)
    {
        try {
            $tutorias = Tutoria::with(['profesor.usuario', 'empresa.usuario'])
                ->select('id', 'id_alumno', 'id_profesor', 'id_empresa', 'fecha_inicio', 'fecha_fin', 'estado')
                ->where('id_alumno', $id)
                ->get();

            if (!is_numeric($id) || (int) $id <= 0) {
                $response = [
                    'response' => 400,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'El ID proporcionado no es válido.'
                ];
                return response()->json($response, 400);
            }

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

    public function listTutorialByTeacherAPI($id)
    {
        try {
            $tutorias = Tutoria::with(['alumno.usuario', 'empresa.usuario'])
                ->select('id', 'id_alumno', 'id_profesor', 'id_empresa', 'fecha_inicio', 'fecha_fin', 'estado')
                ->where('id_profesor', $id)
                ->get();

            if (!is_numeric($id) || (int) $id <= 0) {
                $response = [
                    'response' => 400,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'El ID proporcionado no es válido.'
                ];
                return response()->json($response, 400);
            }

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

    public function createTutorialAPI(Request $request)
    {
        try {
            $data = $request->validate([
                'id_alumno' => 'required|integer|min:1|exists:alumno,id',
                'id_profesor' => 'required|integer|min:1|exists:profesor,id',
                'id_empresa' => 'required|integer|min:1|exists:empresa,id',
                'fecha_inicio' => 'required|date|date_format:Y-m-d H:i:s|after_or_equal:today',
                'fecha_fin' => 'nullable|date|date_format:Y-m-d H:i:s|after:fecha_inicio',
                'estado' => 'required|in:Activa,Finalizada,Cancelada',
            ], [
                'id_alumno.required' => 'El alumno es obligatorio.',
                'id_alumno.integer' => 'El identificador del alumno debe ser un número entero.',
                'id_alumno.min' => 'El identificador del alumno debe ser mayor que 0.',
                'id_alumno.exists' => 'El alumno no existe.',
                'id_profesor.required' => 'El profesor es obligatorio.',
                'id_profesor.integer' => 'El identificador del profesor debe ser un número entero.',
                'id_profesor.min' => 'El identificador del profesor debe ser mayor que 0.',
                'id_profesor.exists' => 'El profesor no existe.',
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

            $tutoria = Tutoria::create($data);

            if ($tutoria) {
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
}
