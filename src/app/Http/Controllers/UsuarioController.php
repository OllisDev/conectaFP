<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function listUserAPI()
    {
        try {
            $usuarios = Usuario::select('id', 'nombre', 'apellidos', 'contrasena', 'email', 'fecha_nacimiento', 'activo', 'fecha_registro')->get();

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

    public function listUserByIdAPI()
    {
        try {

        } catch (\Illuminate\Validation\ValidationException $e) {

        }
    }

    public function createUserAPI()
    {

    }

    public function updateUserAPI()
    {

    }

    public function deleteUserAPI()
    {

    }
}
