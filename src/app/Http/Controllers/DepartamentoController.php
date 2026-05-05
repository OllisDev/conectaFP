<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    // listar todos los departamentos
    public function listDepartmentAPI()
    {
        try {
            $departamentos = Departamento::select('id', 'nombre')
                ->get();

            if ($departamentos) {
                $response = [
                    'response' => 200,
                    'success' => true,
                    'status' => 'ok',
                    'message' => 'Departamento',
                    'departamentos' => $departamentos
                ];
                return response()->json($response, 200);
            } else {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No existe ningún departamento.'
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

    // crear departamentos
    public function createDepartmentAPI(Request $request)
    {
        try {
            $data = $request->validate([
                'nombre' => 'required|string|max:100'
            ], [
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.max' => 'El nombre no puede superar los 100 caracteres.'
            ]);

            $departamento = Departamento::create($data);

            if ($departamento) {
                $response = [
                    'response' => 201,
                    'success' => true,
                    'status' => 'ok',
                    'message' => 'Se ha creado el departamento correctamente.'
                ];
                return response()->json($response, 201);
            } else {
                $response = [
                    'response' => 500,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No se pudo crear el departamento.'
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
