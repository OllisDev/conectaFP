<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmpresaController extends Controller
{
    public function listCompanyAPI()
    {
        try {
            $empresas = Empresa::select('id', 'id_usuario', 'descripcion', 'sector', 'direccion', 'web', 'activo')->get();

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
            $empresa = Empresa::select('id', 'id_usuario', 'descripcion', 'sector', 'direccion', 'web', 'activo')->where('id', $id)->first();

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

    public function registerCompanyAPI(Request $request)
    {
        try {
            $data = $request->validate([
                'nombre' => 'required|string|max:50',
                'apellidos' => 'required|string|max:100',
                'contrasena' => 'required|string|min:8|max:255',
                'email' => 'required|email|max:100|unique:usuario,email',
                'fecha_nacimiento' => 'required|date|before:today',
                'descripcion' => 'required|string',
                'sector' => 'required|string|max:50',
                'direccion' => 'required|string|max:255',
                'web' => 'required|string|max:255',
            ], [
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.max' => 'El nombre no puede superar los 50 caracteres.',
                'apellidos.required' => 'Los apellidos son obligatorios.',
                'apellidos.max' => 'Los apellidos no pueden superar los 100 caracteres.',
                'contrasena.required' => 'La contraseña es obligatoria.',
                'contrasena.min' => 'La contraseña debe tener al menos 8 caracteres.',
                'email.required' => 'El email es obligatorio.',
                'email.email' => 'El formato del email no es válido.',
                'email.unique' => 'Este email ya está registrado.',
                'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
                'fecha_nacimiento.date' => 'El formato de la fecha no es válido.',
                'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
                'descripcion.required' => 'La descripción es obligatorio.',
                'sector.required' => 'El sector es obligatorio.',
                'direccion' => 'La dirección es obligatorio.',
                'direccion.max' => 'La dirección no puede superar los 255 caracteres.',
                'web.required' => 'La web es obligatorio.',
                'web.max' => 'La web no puede superar los 255 caracteres.'
            ]);

            $response = DB::transaction(function () use ($data) {
                $usuario = Usuario::create([
                    'nombre' => $data['nombre'],
                    'apellidos' => $data['apellidos'],
                    'contrasena' => Hash::make($data['contrasena']),
                    'email' => $data['email'],
                    'fecha_nacimiento' => $data['fecha_nacimiento'],
                    'api_token' => Str::random(60),
                ]);

                Empresa::create([
                    'id_usuario' => $usuario->id,
                    'descripcion' => $data['descripcion'],
                    'sector' => $data['sector'],
                    'direccion' => $data['direccion'],
                    'web' => $data['web']
                ]);

                return response()->json([
                    'response' => 201,
                    'success' => true,
                    'status' => 'ok',
                    'message' => 'Cuenta de empresa creada correctamente.'
                ]);
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
}
