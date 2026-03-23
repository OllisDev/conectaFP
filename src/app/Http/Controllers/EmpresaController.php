<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use Illuminate\Support\Facades\Auth;

class EmpresaController extends Controller
{
    public function listCompanyAPI()
    {
        try {
            $empresas = Empresa::select('id', 'id_usuario', 'nombre', 'descripcion', 'sector', 'direccion', 'web', 'activo')->get();

            if ($empresas) {
                $response = [
                    'response' => 200,
                    'success' => true,
                    'status' => 'ok',
                    'message' => 'Empresa',
                    'empresas' => $empresas
                ];
                return response()->json($response, 200);
            } else {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No existe ninguna empresa.'
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

    public function listCompanyByIdAPI($id)
    {
        try {
            $empresa = Empresa::select('id', 'id_usuario', 'nombre', 'descripcion', 'sector', 'direccion', 'web', 'activo')->where('id', $id)->first();

            if (!$empresa) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'La empresa no existe.'
                ];
                return response()->json($response, 404);
            } else {
                $response = [
                    'response' => 200,
                    'success' => true,
                    'status' => 'ok',
                    'empresa' => $empresa
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

    public function createCompanyAPI(Request $request)
    {
        try {
            $data = $request->validate([
                'nombre' => 'required|string|max:100',
                'descripcion' => 'required|string',
                'sector' => 'required|string|max:50',
                'direccion' => 'required|string|max:255',
                'web' => 'required|string|max:100',
                'activo' => 'required|boolean'
            ]);

            $data['id_usuario'] = Auth::id();
            $empresa = Empresa::create($data);

            if ($empresa) {
                $response = [
                    'response' => 201,
                    'success' => true,
                    'status' => 'ok',
                    'message' => 'Se ha creado la empresa correctamente.'
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

    public function updateCompanyAPI(Request $request, $id)
    {
        try {
            $company = Empresa::find($id);

            if (!$company) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'La empresa no existe'
                ];
                return response()->json($response, 404);
            }

            $data = $request->validate([
                'nombre' => 'required|string|max:100',
                'descripcion' => 'required|string',
                'sector' => 'required|string|max:50',
                'direccion' => 'required|string|max:255',
                'web' => 'required|string|max:100',
                'activo' => 'required|boolean'
            ]);

            $data['id_usuario'] = Auth::id();
            $company->update($data);

            $response = [
                'response' => 200,
                'success' => true,
                'status' => 'ok',
                'message' => 'La empresa se ha actualizado correctamente.'
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

    public function deleteCompanyAPI($id)
    {
        try {
            $company = Empresa::where('id', $id)->where('id_usuario', Auth::id())->first();

            if (!$company) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No existe la empresa.'
                ];
                return response()->json($response, 404);
            }

            $company->delete();

            $response = [
                'response' => 200,
                'success' => true,
                'status' => 'ok',
                'message' => 'La empresa ha sido actualizado correctamente.'
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
