<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SolicitudController extends Controller
{
    public function listRequestByTeacherAPI($id)
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

            $solicitud = Solicitud::with(['oferta.empresa.usuario'])->select('id', 'id_oferta', 'id_alumno', 'fecha_solicitud', 'estado')->where('id_profesor', $id)->get();

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

    public function updateRequestAPI(Request $request, $id)
    {
        try {
            $solicitud = Solicitud::find($id);

            if (!$solicitud) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'La solicitud no existe.'
                ];
                return response()->json($response, 400);
            }

            $data = $request->validate([
                'estado' => 'required|in:Pendiente,Revision,Aceptada,Rechazada'
            ], [
                'estado.required' => 'El estado es obligatorio.',
                'estado.in' => 'El estado debe ser "Pendiente", "Revision", "Aceptada" o "Rechazada".'
            ]);

            $solicitud->update($data);

            $response = [
                'response' => 200,
                'success' => true,
                'status' => 'ok',
                'message' => 'La solicitud se ha actualizado correctamente.'
            ];
            return response()->json($response, 200);

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
