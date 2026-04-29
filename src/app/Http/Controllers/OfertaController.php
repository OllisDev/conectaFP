<?php

namespace App\Http\Controllers;

use App\Models\Oferta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfertaController extends Controller
{
    public function listOfferAPI()
    {
        try {
            $ofertas = Oferta::with('empresa.usuario')->select('id', 'id_empresa', 'titulo', 'descripcion', 'requisitos', 'modalidad', 'fecha_publicacion', 'estado')->get();

            if ($ofertas->isEmpty()) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No existe ninguna oferta.'
                ];
                return response()->json($response, 404);
            } else {
                $response = [
                    'response' => 200,
                    'success' => true,
                    'status' => 'ok',
                    'message' => 'Oferta',
                    'alumnos' => $ofertas
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

    public function listOfferByIdAPI($id)
    {
        try {
            $oferta = Oferta::select('id', 'id_empresa', 'titulo', 'descripcion', 'requisitos', 'modalidad', 'fecha_publicacion', 'estado')->where('id', $id)->first();

            if (!is_numeric($id) || (int) $id <= 0) {
                $response = [
                    'response' => 400,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'El ID proporcionado no es válido.'
                ];
                return response()->json($response, 400);
            }

            if (!$oferta) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'La oferta no existe.'
                ];
                return response()->json($response, 404);
            } else {
                $response = [
                    'response' => 200,
                    'success' => true,
                    'status' => 'ok',
                    'oferta' => $oferta
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

    public function filterOfferAPI(Request $request)
    {
        try {

            $query = Oferta::with(['empresa.usuario']);

            if ($request->filled('modalidad')) {
                $query->where('modalidad', $request->modalidad);
            }

            if ($request->filled('id_sector')) {
                $query->whereHas('empresa', function ($q) use ($request) {
                    $q->where('id_sector', $request->id_sector);
                });
            }

            if ($request->filled('titulo')) {
                $query->where('titulo', 'like', '%' . $request->titulo . '%');
            }

            if ($request->filled('id_profesor')) {
                $query->whereHas('solicitudes', function ($q) use ($request) {
                    $q->where('id_profesor', $request->id_profesor);
                });
            }

            $ofertas = $query->get();

            if ($ofertas->isEmpty()) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No se encontraron ofertas con los filtros aplicados.'
                ];
                return response()->json($response, 404);
            }

            $response = [
                'response' => 200,
                'success' => true,
                'status' => 'ok',
                'message' => 'Oferta',
                'ofertas' => $ofertas
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

    public function listOfferByCompanyAPI()
    {
        try {
            $user = Auth::user();
            $empresa = $user->empresa;

            if (!$empresa) {
                return response()->json([
                    'response' => 401,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No autenticado o el usuario no es la empresa.'
                ], 401);
            }

            $ofertas = Oferta::where('id_empresa', $empresa->id)->get();

            if ($ofertas->isEmpty()) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No existen ofertas.'
                ];
                return response()->json($response, 404);
            }

            $response = [
                'response' => 200,
                'success' => true,
                'status' => 'ok',
                'ofertas' => $ofertas
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

    public function createOfferAPI(Request $request)
    {
        try {
            $data = $request->validate([
                'id_empresa' => 'required|integer|min:1|exists:empresa,id',
                'titulo' => 'required|string|min:2|max:255',
                'descripcion' => 'required|string|min:10|max:5000',
                'requisitos' => 'required|string|min:2|max:5000',
                'modalidad' => 'in:Presencial,Remoto,Híbrido',
                'estado' => 'nullable|in:Abierta,Cerrada,Pausada'
            ], [
                'id_empresa.required' => 'La empresa es obligatoria.',
                'id_empresa.exists' => 'La empresa no existe.',
                'id_empresa.integer' => 'El identificador de la empresa debe ser un número entero.',
                'id_empresa:min' => 'La empresa debe tener por lo menos 1 caracter.',
                'titulo.required' => 'El titulo es obligatorio',
                'titulo.min' => 'El título debe tener al menos 2 caracteres.',
                'titulo.max' => 'El título no debe tener mas de 255 caracteres.',
                'descripcion.required' => 'La descripción es obligatorio.',
                'descripcion.min' => 'La descripción debe tener al menos 10 caracteres.',
                'descripcion.max' => 'La descripción debe tener menos de 5000 caracteres.',
                'requisitos.required' => 'Los requisitos aon obligatorios.',
                'requisitos.min' => 'Los requisitos deben tener al menos 2 caracteres.',
                'requisitos.max' => 'Los requisitos deben tener menos de 5000 caracteres',
                'modalidad.required' => 'La modalidad es obligatoria.',
                'modalidad.min' => 'Debes seleccionar al menos una modalidad.',
                'modalidad.in' => 'La modalidad debe ser "Presencial", "Remoto" o "Híbrido".',
                'estado.in' => 'El estado debe ser "Abierta", "Cerrada" o "Pausada".'
            ]);

            $data['fecha_publicacion'] = now()->toDateString();
            $oferta = Oferta::create($data);

            if ($oferta) {
                $response = [
                    'response' => 201,
                    'success' => true,
                    'status' => 'ok',
                    'message' => 'Se ha creado la oferta correctamente.'
                ];
                return response()->json($response, 201);
            } else {
                $response = [
                    'response' => 500,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No se pudo crear la oferta.'
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
