<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SolicitudController extends Controller
{
    public function listRequestByTeacherAPI()
    {
        try {
            $user = Auth::user();
            $idProfesor = $user->profesor->id;

            $alumnosAsignados = Alumno::where('id_profesor', $idProfesor)->pluck('id')->toArray();

            $solicitudes = Solicitud::with(['oferta.empresa.usuario'])
                ->select('id', 'id_oferta', 'id_alumno', 'fecha_solicitud', 'estado')
                ->where('id_profesor', $idProfesor)
                ->whereIn('id_alumno', $alumnosAsignados)
                ->get();

            if ($solicitudes->isEmpty()) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No se encontraron solicitudes para los alumnos.'
                ];
                return response()->json($response, 404);
            }

            $response = [
                'response' => 200,
                'success' => true,
                'status' => 'ok',
                'solicitud' => $solicitudes
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
                'id_oferta' => 'required|integer|min:1|exists:oferta,id',
                'id_empresa' => 'required|integer|min:1|exists:empresa,id',
                'alumnos' => 'required|array|min:1',
                'alumnos.*' => 'required|integer|min:1|distinct|exists:alumno,id'
            ], [
                'id_oferta.required' => 'La oferta es obligatoria.',
                'id_oferta.exists' => 'La oferta no existe.',
                'id_oferta.integer' => 'El identificador de la oferta debe ser un número entero.',
                'id_empresa.required' => 'La empresa es obligatoria.',
                'id_empresa.exists' => 'La empresa no existe.',
                'id_empresa.integer' => 'El identificador de la empresa debe ser un número entero.',
                'alumnos.array' => 'El campo alumnos debe ser un array.',
                'alumnos.min' => 'Debes seleccionar al menos un alumno.',
                'alumnos.*.required' => 'El alumno es obligatorio.',
                'alumnos.*.integer' => 'El identificador del alumno debe ser un número entero.',
                'alumnos.*.min' => 'El identificador del alumno debe ser mayor que 0.',
                'alumnos.*.distinct' => 'No puedes seleccionar el mismo alumno más de una vez.',
                'alumnos.*.exists' => 'El alumno seleccionado no existe.',
            ]);

            $alumnosAsignados = Alumno::where('id_profesor', $idProfesor)->pluck('id')->toArray();
            $alumnosNoValidos = array_diff($data['alumnos'], $alumnosAsignados);
            if (count($alumnosNoValidos) > 0) {
                return response()->json([
                    'response' => 400,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'Los siguientes alumnos no están asignados a este profesor: '
                ], 400);
            }

            $yaExisten = [];
            foreach ($data['alumnos'] as $idAlumno) {
                $existe = Solicitud::where('id_oferta', $data['id_oferta'])
                    ->where('id_alumno', $idAlumno)
                    ->where('id_profesor', $idProfesor)
                    ->exists();
                if ($existe) {
                    $yaExisten[] = $idAlumno;
                }
            }
            if (count($yaExisten) > 0) {
                return response()->json([
                    'response' => 400,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'Ya existe una solicitud previa.'
                ], 400);
            }

            $response = DB::transaction(function () use ($data, $idProfesor) {
                foreach ($data['alumnos'] as $idAlumno) {
                    Solicitud::create([
                        'id_oferta' => $data['id_oferta'],
                        'id_alumno' => $idAlumno,
                        'id_empresa' => $data['id_empresa'],
                        'id_profesor' => $idProfesor
                    ]);
                }
                return response()->json([
                    'response' => 201,
                    'success' => true,
                    'status' => 'ok',
                    'message' => 'Solicitud enviada correctamente.'
                ], 201);
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
