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
            $empresas = Empresa::select('id', 'id_usuario', 'id_sector', 'nif', 'descripcion', 'direccion', 'web', 'activo')->get();

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
            $empresa = Empresa::select('id', 'id_usuario', 'id_sector', 'nif', 'descripcion', 'direccion', 'web', 'activo')->where('id', $id)->first();

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

    public function registerCompanyAPI(Request $request)
    {
        try {
            $data = $request->validate([
                'nombre' => 'required|string|min:2|max:50|regex:/^[\p{L}\s\-\']+$/u',
                'contrasena' => [
                    'required',
                    'string',
                    'min:8',
                    'max:255',
                    \Illuminate\Validation\Rules\Password::min(8)
                        ->mixedCase()
                        ->numbers()
                        ->symbols()
                ],
                'email' => 'required|email:rfc,dns|lowercase|max:100|unique:usuario,email',
                'telefono' => 'required|string|regex:/^[6-9][0-9]{8}$/|unique:usuario,telefono',
                'id_sector' => 'required|integer|min:1|exists:sector,id',
                'nif' => 'required|string|size:9|regex:/^[0-9]{8}[A-Z]$/|nif|unique:empresa,nif',
                'descripcion' => 'required|string|min:10|max:5000',
                'direccion' => 'required|string|max:255',
                'web' => 'required|string|url|max:100',
            ], [
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.max' => 'El nombre no puede superar los 50 caracteres.',
                'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
                'nombre.regex' => 'El nombre solo puede contener letras, espacios, guiones y apóstrofes.',
                'contrasena.required' => 'La contraseña es obligatoria.',
                'contrasena.min' => 'La contraseña debe tener al menos 8 caracteres.',
                'contrasena.max' => 'La contraseña no puede superar los 255 caracteres.',
                'contrasena.password' => 'La contraseña debe contener mayúsculas, minúsculas, números y símbolos.',
                'email.required' => 'El email es obligatorio.',
                'email.email' => 'El formato del email no es válido.',
                'email.unique' => 'Este email ya está registrado con otra cuenta.',
                'email.max' => 'El email no puede superar los 100 caracteres.',
                'email.lowercase' => 'El email debe estar en minúsculas.',
                'telefono.required' => 'El teléfono es obligatorio.',
                'telefono.regex' => 'El teléfono no es válido.',
                'telefono.unique' => 'El teléfono ya está registrado con otra cuenta.',
                'id_sector.required' => 'El sector es obligatorio.',
                'id_sector.exists' => 'El sector no existe.',
                'id_sector.min' => 'El sector debe tener al menos 1 caracter.',
                'id_sector.integer' => 'El identificador del sector debe ser un número entero.',
                'nif.required' => 'El NIF es obligatorio.',
                'nif.size' => 'El NIF debe tener 9 caractéres.',
                'nif.regex' => 'El formato del NIF no es valido.',
                'nif.nif' => 'NIF incorrecto.',
                'nif.unique' => 'El NIF está registrado con otra cuenta.',
                'descripcion.required' => 'La descripción es obligatorio.',
                'descripcion.min' => 'La descripción debe tener al menos 10 caracteres.',
                'descripcion.max' => 'La descripción debe tener menos de 5000 caracteres.',
                'direccion.required' => 'La dirección es obligatorio.',
                'direccion.max' => 'La dirección no puede superar los 255 caracteres.',
                'web.required' => 'La web es obligatorio.',
                'web.url' => 'Formato incorrecto de la web.',
                'web.max' => 'La web no puede superar los 100 caracteres.'
            ]);

            $response = DB::transaction(function () use ($data) {
                $usuario = Usuario::create([
                    'nombre' => $data['nombre'],
                    'contrasena' => Hash::make($data['contrasena']),
                    'email' => $data['email'],
                    'telefono' => $data['telefono'],
                    'api_token' => Str::random(60),
                ]);

                Empresa::create([
                    'id_usuario' => $usuario->id,
                    'id_sector' => $data['id_sector'],
                    'nif' => $data['nif'],
                    'descripcion' => $data['descripcion'],
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
