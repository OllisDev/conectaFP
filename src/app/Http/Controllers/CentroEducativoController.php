<?php

namespace App\Http\Controllers;

use App\Models\CentroEducativo;
use Illuminate\Http\Request;

class CentroEducativoController extends Controller
{

    // listar todos los centros educativos
    public function listSchoolAPI()
    {
        try {
            $centros = CentroEducativo::select('id', 'nombre', 'localidad', 'provincia', 'codigo_centro')
                ->get();

            if ($centros) {
                $response = [
                    'response' => 200,
                    'success' => true,
                    'status' => 'ok',
                    'message' => 'Centro educativo',
                    'centros' => $centros
                ];
                return response()->json($response, 200);
            } else {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No existe ningún centro educativo.'
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

    // crear centros educativos
    public function createSchoolAPI(Request $request)
    {
        try {
            $data = $request->validate([
                'nombre' => 'required|string|max:50',
                'localidad' => 'required|string|max:100',
                'provincia' => 'required|string|max:100',
                'codigo_centro' => 'required|string|max:20|unique:centro_educativo,codigo_centro'
            ], [
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.max' => 'El nombre no puede superar los 50 caracteres.',
                'localidad.required' => 'La localidad es obligatorio.',
                'localidad.max' => 'La localidad no puede superar los 100 caracteres.',
                'provincia.required' => 'La provincia es obligatorio.',
                'provincia.max' => 'La provincia no puede superar los 100 caracteres.',
                'codigo_centro.required' => 'El código del centro es obligatorio.',
                'codigo_centro.max' => 'El código del centro no puede superar los 20 dígitos.',
                'codigo_centro.unique' => 'Este centro educactivo ya está registrado.'
            ]);

            $centro = CentroEducativo::create($data);

            if ($centro) {
                $response = [
                    'response' => 201,
                    'success' => true,
                    'status' => 'ok',
                    'message' => 'Se ha creado el centro educativo correctamente.'
                ];
                return response()->json($response, 201);
            } else {
                $response = [
                    'response' => 500,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No se pudo crear el centro educativo.'
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
