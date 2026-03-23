<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alumno;
use Illuminate\Support\Facades\Auth;

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
                    'usuario' => $alumno
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
                    'message' => 'Se ha creado un alumno correctamente.'
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
                'success' => false,
                'status' => 'error',
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
}
