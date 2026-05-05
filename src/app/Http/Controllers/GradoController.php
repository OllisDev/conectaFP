<?php

namespace App\Http\Controllers;

use App\Models\Grado;
use Illuminate\Http\Request;

class GradoController extends Controller
{
    // listar todos los grados formativos disponibles
    public function listDegreeAPI()
    {
        try {
            $grados = Grado::select('id', 'nombre', 'tipo', 'familia_profesional', 'codigo_grado')->get();

            if ($grados) {
                $response = [
                    'response' => 200,
                    'success' => true,
                    'status' => 'ok',
                    'message' => 'Grado',
                    'grados' => $grados
                ];
                return response()->json($response, 200);
            } else {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No existe ningún grado.'
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
            return response()->json($response, 400);
        }
    }

    // crear grado formativo
    public function createDegreeAPI(Request $request)
    {
        try {
            $data = $request->validate([
                'nombre' => 'required|string|max:50',
                'tipo' => 'required|in:Grado medio,Grado superior',
                'familia_profesional' => 'required|string|max:100',
                'codigo_grado' => 'required|string|max:20',
            ], [
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.max' => 'El nombre no puede superar los 100 caracteres.',
                'tipo.required' => 'El tipo es obligatorio.',
                'tipo.in' => 'El tipo debe ser "Grado medio" o "Grado superior"',
                'familia_profesional' => 'La familia profesional es obligatorio.',
                'familia_profesional.max' => 'La familia profesional no puede superar los 100 caracteres.',
                'codigo_grado.required' => 'El código del grado es obligatorio.',
                'codigo_grado.max' => 'El código del grado no puede superar los 20 dígitos.'
            ]);

            $grado = Grado::create($data);

            if ($grado) {
                $response = [
                    'response' => 201,
                    'success' => true,
                    'status' => 'ok',
                    'message' => 'Se ha creado el grado correctamente.'
                ];
                return response()->json($response, 201);
            } else {
                $response = [
                    'response' => 500,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No se pudo crear el grado.'
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
