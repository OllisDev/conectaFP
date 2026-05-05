<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use Illuminate\Http\Request;

class SectorController extends Controller
{
    // listar todos los sectores empresariales disponibles
    public function listSectorAPI()
    {
        try {
            $sectores = Sector::select('id', 'nombre')->get();

            if ($sectores) {
                $response = [
                    'response' => 200,
                    'success' => true,
                    'status' => 'ok',
                    'message' => 'Sector',
                    'sectores' => $sectores
                ];
                return response()->json($response, 200);
            } else {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No existe ningún sector.'
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

    // crear sector empresarial
    public function createSectorAPI(Request $request)
    {
        try {
            $data = $request->validate([
                'nombre' => 'required|string|max:100'
            ], [
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.max' => 'El nombre no puede superar los 100 caracteres.'
            ]);

            $sector = Sector::create($data);

            if ($sector) {
                $response = [
                    'response' => 201,
                    'success' => true,
                    'status' => 'ok',
                    'message' => 'Se ha creado el sector correctamente.'
                ];
                return response()->json($response, 201);
            } else {
                $response = [
                    'response' => 500,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No se pudo crear el sector.'
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
