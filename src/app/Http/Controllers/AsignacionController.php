<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Asignacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function PHPSTORM_META\map;

class AsignacionController extends Controller
{
    public function listAssignmentAPI()
    {
        try {
            $user = Auth::user();
            $profesor = $user->profesor;
            $asignaciones = Asignacion::with('alumno.usuario', 'profesor.usuario', 'empresa.usuario')
                ->where('id_profesor', $profesor->id)
                ->select('id', 'id_alumno', 'id_profesor', 'id_empresa', 'estado')
                ->get();

            if ($asignaciones->isEmpty()) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No existe ninguna asignación.'
                ];
                return response()->json($response, 404);
            } else {
                $response = [
                    'response' => 200,
                    'success' => true,
                    'status' => 'ok',
                    'message' => 'Empresa',
                    'asignaciones' => $asignaciones
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

    public function listAssignmentByStudentAPI($idAlumno)
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
            $profesor = $user->profesor;

            if (!is_numeric($idAlumno) || (int) $idAlumno <= 0) {
                $response = [
                    'response' => 400,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'El ID proporcionado no es válido.'
                ];
                return response()->json($response, 400);
            }

            if (!$idAlumno) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'El alumno no existe.'
                ];
                return response()->json($response, 404);
            }

            $asignaciones = Asignacion::with(['alumno.usuario', 'empresa'])
                ->where('id_profesor', $profesor->id)
                ->where('id_alumno', $idAlumno)
                ->get();

            if ($asignaciones->isEmpty()) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No se encontraron asignaciones de ese alumno con el profesor.'
                ];
                return response()->json($response, 404);
            }

            $response = [
                'response' => 200,
                'success' => true,
                'status' => 'ok',
                'message' => 'asignaciones',
                'asignaciones' => $asignaciones
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

    public function listAssignmentByStudentForCompanyAPI($idAlumno)
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
            $profesor = $user->profesor;

            if (!is_numeric($idAlumno) || (int) $idAlumno <= 0) {
                $response = [
                    'response' => 400,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'El ID proporcionado no es válido.'
                ];
                return response()->json($response, 400);
            }

            if (!$idAlumno) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'El alumno no existe.'
                ];
                return response()->json($response, 404);
            }

            $asignaciones = Asignacion::with('empresa')
                ->where('id_profesor', $profesor->id)
                ->where('id_alumno', $idAlumno)
                ->get();

            if ($asignaciones->isEmpty()) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No se encontraron asignaciones de ese alumno con el profesor.'
                ];
                return response()->json($response, 404);
            }

            $response = [
                'response' => 200,
                'success' => true,
                'status' => 'ok',
                'message' => 'asignaciones',
                'asignaciones' => $asignaciones
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
    public function createAssignmentAPI(Request $request)
    {
        try {
            $data = $request->validate([
                'id_alumno' => 'required|integer|min:1|exists:alumno,id',
                'id_empresa' => 'required|integer|min:1|exists:empresa,id',
                'estado' => 'required|in:Activo,Finalizado'
            ], [
                'id_alumno.required' => 'El alumno es obligatorio.',
                'id_alumno.exists' => 'El alumno no existe.',
                'id_alumno.integer' => 'El identificador del alumno debe ser un número entero.',
                'id_empresa.required' => 'La empresa es obligatoria.',
                'id_empresa.exists' => 'La empresa no existe.',
                'id_empresa.integer' => 'El identificador de la empresa debe ser un número entero.',
                'estado.required' => 'El estado es obligatorio.',
                'estado.in' => 'El estado debe ser "Activo" o "Finalizado".'
            ]);

            $user = Auth::user();
            $profesor = $user->profesor;

            $asignacionExiste = Asignacion::where('id_alumno', $data['id_alumno'])
                ->where('id_empresa', $data['id_empresa'])
                ->where('id_profesor', $profesor->id)
                ->exists();

            if ($asignacionExiste) {
                $response = [
                    'response' => 409,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'Ya existe una asignación con estos datos.'
                ];
                return response()->json($response, 409);
            }

            Asignacion::create([
                'id_alumno' => $data['id_alumno'],
                'id_empresa' => $data['id_empresa'],
                'id_profesor' => $profesor->id,
                'estado' => $data['estado']
            ]);

            $response = [
                'response' => 201,
                'success' => true,
                'status' => 'ok',
                'message' => 'La asignación se ha creado correctamente.'
            ];
            return response()->json($response, 201);

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

    public function updateAssignmentAPI(Request $request, $id)
    {
        try {
            $asignacion = Asignacion::find($id);

            if (!$asignacion) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'La asignación no existe.'
                ];
                return response()->json($response, 404);
            }

            $data = $request->validate([
                'estado' => 'required|in:Activo,Finalizado'
            ], [
                'estado.required' => 'El estado es obligatorio.',
                'estado.in' => 'El estado deber "Activo" o "Finalizado".'
            ]);

            $user = Auth::user();
            $profesor = $user->profesor;

            $asignacion->update([
                'id_profesor' => $profesor->id,
                'estado' => $data['estado']
            ]);

            $response = [
                'response' => 200,
                'success' => true,
                'status' => 'ok',
                'message' => 'La asignación ha sido actualizado correctamente.'
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

    public function deleteAssignmentAPI($id)
    {
        try {
            $asignacion = Asignacion::where('id', $id)->first();

            if (!is_numeric($id) || (int) $id <= 0) {
                $response = [
                    'response' => 400,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'El ID proporcionado no es válido.'
                ];
                return response()->json($response, 400);
            }

            if (!$asignacion) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No existe la asignación.'
                ];
                return response()->json($response, 404);
            }

            $asignacion->delete();

            $response = [
                'response' => 200,
                'success' => true,
                'status' => 'ok',
                'message' => 'La asignación ha sido eliminada correctamente.'
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
