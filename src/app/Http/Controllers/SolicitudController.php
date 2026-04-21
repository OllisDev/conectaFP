<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use Illuminate\Http\Request;

class SolicitudController extends Controller
{
    public function listRequestByStudentAPI($id)
    {
        try {
            if (!is_numeric($id) || (int) $id <= 0) {
                $response = [
                    'response' => 400,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'El ID proporcionado no es válido.'
                ];
                return response()->json($response, 400);
            }

            $solicitud = Solicitud::with(['oferta.empresa.usuario'])->select('id', 'id_oferta', 'id_alumno', 'fecha_solicitud', 'estado')->where('id_alumno', $id)->get();

            if ($solicitud->isEmpty()) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No se encontraron solicitudes para este alumno.'
                ];
                return response()->json($response, 404);
            }

            $response = [
                'response' => 200,
                'success' => true,
                'status' => 'ok',
                'solicitud' => $solicitud
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

    public function requestAPI(Request $request)
    {
        try {
            $data = $request->validate([
                'id_oferta' => [
                    'required',
                    'integer',
                    'min:1',
                    'exists:oferta,id',
                    \Illuminate\Validation\Rule::unique('solicitud')->where(fn($q) => $q->where('id_alumno', $request->id_alumno)),
                ],
                'id_alumno' => 'required|integer|min:1|exists:alumno,id',
            ], [
                'id_oferta.required' => 'La oferta es obligatoria.',
                'id_oferta.exists' => 'La oferta no existe.',
                'id_oferta.integer' => 'El identificador de la oferta debe ser un número entero.',
                'id_oferta.unique' => 'Ya has realizado una solicitud para esta oferta.',
                'id_alumno.required' => 'El alumno es obligatorio.',
                'id_alumno.exists' => 'El alumno no existe.',
                'id_alumno.integer' => 'El identificador del alumno debe ser un número entero.',
            ]);

            $solicitud = Solicitud::create($data);

            if ($solicitud) {
                $response = [
                    'response' => 201,
                    'success' => true,
                    'status' => 'ok',
                    'message' => 'Se ha creado la solicitud correctamente.'
                ];
                return response()->json($response, 201);
            } else {
                $response = [
                    'response' => 500,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No se pudo crear la solicitud.'
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

    public function deleteRequestAPI($id)
    {
        try {
            $solicitud = Solicitud::where('id', $id)->first();

            if (!$solicitud) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No existe la solicitud.'
                ];
                return response()->json($response, 404);
            }

            $solicitud->delete();

            $response = [
                'response' => 200,
                'success' => true,
                'status' => 'ok',
                'message' => 'La solicitud ha sido eliminado correctamente.'
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
