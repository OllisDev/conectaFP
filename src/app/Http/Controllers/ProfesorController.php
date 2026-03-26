<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profesor;
use Illuminate\Support\Facades\Auth;

class ProfesorController extends Controller
{
    public function listTeacherAPI()
    {
        try {
            $profesores = Profesor::select('id', 'id_usuario', 'departamento', 'telefono')->get();

            if ($profesores) {
                $response = [
                    'response' => 200,
                    'success' => true,
                    'status' => 'ok',
                    'message' => 'Profesor',
                    'profesores' => $profesores
                ];
                return response()->json($response, 200);
            } else {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No existe ningún profesor.'
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

    public function listTeacherByIdAPI($id)
    {
        try {
            $profesor = Profesor::select('id', 'id_usuario', 'departamento', 'telefono')->where('id', $id)->first();

            if (!$profesor) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'El profesor no existe.'
                ];
                return response()->json($response, 404);
            } else {
                $response = [
                    'response' => 200,
                    'success' => true,
                    'status' => 'ok',
                    'profesor' => $profesor
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

    public function createTeacherAPI(Request $request)
    {
        try {
            $data = $request->validate([
                'departamento' => 'required|string|max:100',
                'telefono' => 'required|string|max:20',
            ]);

            $data['id_usuario'] = Auth::id();
            $profesor = Profesor::create($data);

            if ($profesor) {
                $response = [
                    'response' => 201,
                    'success' => true,
                    'status' => 'ok',
                    'message' => 'Se ha creado el profesor correctamente.'
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

    public function updateTeacherAPI(Request $request, $id)
    {
        try {
            $profesor = Profesor::find($id);

            if (!$profesor) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'El profesor no existe.'
                ];
                return response()->json($response, 404);
            }

            $data = $request->validate([
                'departamento' => 'required|string|max:100',
                'telefono' => 'required|string|max:20',
            ]);

            $data['id_usuario'] = Auth::id();
            $profesor->update($data);

            $response = [
                'response' => 200,
                'success' => true,
                'status' => 'ok',
                'message' => 'El profesor se ha actualizado correctamente.'
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

    public function deleteTeacherAPI($id)
    {
        try {
            $profesor = Profesor::where('id', $id)->where('id_usuario', Auth::id())->first();

            if (!$profesor) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No existe el profesor.'
                ];
                return response()->json($response, 404);
            }

            $profesor->delete();

            $response = [
                'response' => 200,
                'success' => true,
                'status' => 'ok',
                'message' => 'El profesor ha sido eliminado correctamente.'
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
