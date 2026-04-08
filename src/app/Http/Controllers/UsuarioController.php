<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsuarioController extends Controller
{
    public function listUserAPI()
    {
        try {
            $usuarios = Usuario::select('id', 'nombre', 'apellidos', 'contrasena', 'email', 'activo', 'fecha_registro')->get();

            if ($usuarios) {
                $response = [
                    'response' => 200,
                    'success' => true,
                    'status' => 'ok',
                    'message' => 'Usuario',
                    'usuarios' => $usuarios
                ];
                return response()->json($response, 200);
            } else {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No existe ningún usuario.'
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

    public function listUserByIdAPI($id)
    {
        try {
            $usuario = Usuario::select('id', 'nombre', 'apellidos', 'contrasena', 'email', 'activo', 'fecha_registro')->where('id', $id)->first();

            if (!$usuario) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'El usuario no existe.'
                ];
                return response()->json($response, 404);
            } else {
                $response = [
                    'response' => 200,
                    'success' => true,
                    'status' => 'ok',
                    'usuario' => $usuario
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

    public function updateUserAPI(Request $request, $id)
    {
        try {

            $usuario = Usuario::find($id);

            if (!$usuario) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'El usuario no existe.'
                ];
                return response()->json($response, 404);
            }

            $data = $request->validate([
                'nombre' => 'required|string|max:50',
                'apellidos' => 'required|string|max:100',
                'contrasena' => 'required|string|max:255',
                'email' => 'required|string|max:100',
                'fecha_nacimiento' => 'required|date',
                'activo' => 'boolean'
            ]);

            $data['contrasena'] = Hash::make($data['contrasena']);

            $usuario->update($data);

            $response = [
                'response' => 200,
                'success' => true,
                'status' => 'ok',
                'message' => 'El usuario se ha actualizado correctamente.'
            ];
            return response($response, 200);

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

    public function deleteUserAPI($id)
    {
        try {
            $usuario = Usuario::where('id', $id)->first();

            if (!$usuario) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No existe el usuario.'
                ];
                return response()->json($response, 404);
            }

            $usuario->delete();

            $response = [
                'response' => 200,
                'success' => true,
                'status' => 'ok',
                'message' => 'El usuario ha sido eliminado correctamente.'
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
