<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Profesor;
use App\Models\Empresa;
use App\Models\Solicitud;
use App\Notifications\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SolicitudController extends Controller
{
    // listar solicitudes que tiene el profesor logueado con el alumno
    public function listRequestByTeacherAPI()
    {
        try {
            $user = Auth::user();
            $idProfesor = $user->profesor->id;

            // recuperar los alumnos asignados por el profesor logueado
            $alumnosAsignados = Alumno::where('id_profesor', $idProfesor)->pluck('id')->toArray();

            $solicitudes = Solicitud::with([
                'oferta' => function ($q) {
                    $q->where('eliminado', 0);
                },
                'oferta.empresa.usuario',
                'profesor.usuario',
                'alumno.usuario'
            ])
                ->select('id', 'id_oferta', 'id_alumno', 'id_profesor', 'fecha_solicitud', 'estado')
                ->where('id_profesor', $idProfesor)
                ->whereIn('id_alumno', $alumnosAsignados)
                ->whereHas('oferta', function ($q) {
                    $q->where('eliminado', 0);
                })
                ->get();

            if ($solicitudes->isEmpty()) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No se encontraron solicitudes para los alumnos.'
                ];
                return response()->json($response, 404);
            }

            $response = [
                'response' => 200,
                'success' => true,
                'status' => 'ok',
                'solicitud' => $solicitudes
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

    // listar solicitudes que tiene la empresa logueada asignadas por el profesor
    public function listRequestByCompanyAPI()
    {
        try {
            $user = Auth::user();
            $empresa = $user->empresa;

            if (!$empresa) {
                return response()->json([
                    'response' => 401,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No autenticado o el usuario no es una empresa.'
                ], 401);
            }

            $solicitudes = Solicitud::with([
                'oferta' => function ($q) use ($empresa) {
                    $q->where('id_empresa', $empresa->id)->where('eliminado', 0); // filtrar por los que no estan eliminados con el borrado logico
                },
                'alumno' => function ($q) {
                    $q->select('id', 'cv', 'id_usuario', 'id_centro'); // recuperar mediante la relación con alumno el nombre, el centro formativo y el CV
                },
                'alumno.centroEducativo',
                'oferta.empresa.usuario',
                'alumno.usuario',
                'profesor.usuario'
            ])
                ->whereHas('oferta', function ($q) use ($empresa) {
                    $q->where('id_empresa', $empresa->id)->where('eliminado', 0);
                })
                ->get();

            if ($solicitudes->isEmpty()) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No se encontraron solicitudes para los alumnos.'
                ];
                return response()->json($response, 404);
            }

            $response = [
                'response' => 200,
                'success' => true,
                'status' => 'ok',
                'solicitud' => $solicitudes
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

    // listar solicitudes asignadas al alumno logueado
    public function listRequestByStudentAPI()
    {
        try {
            $user = Auth::user();
            if (!$user || !$user->alumno) {
                return response()->json([
                    'response' => 401,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No autenticado o el usuario no es alumno.'
                ], 401);
            }

            $idAlumno = $user->alumno->id;
            $alumno = Alumno::find($idAlumno);

            if (!$alumno || !$alumno->id_profesor) {
                return response()->json([
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'El alumno no tiene profesor asignado.'
                ], 404);
            }

            $idProfesor = $alumno->id_profesor;

            $solicitudes = Solicitud::with([
                'oferta' => function ($q) {
                    $q->where('eliminado', 0);
                },
                'oferta.empresa.usuario',
                'profesor.usuario'
            ])
                ->select('id', 'id_oferta', 'id_alumno', 'id_profesor', 'fecha_solicitud', 'estado')
                ->where('id_profesor', $idProfesor)
                ->where('id_alumno', $idAlumno)
                ->whereHas('oferta', function ($q) {
                    $q->where('eliminado', 0);
                })
                ->get();


            if ($solicitudes->isEmpty()) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No se encontraron solicitudes para los alumnos.'
                ];
                return response()->json($response, 404);
            }

            $response = [
                'response' => 200,
                'success' => true,
                'status' => 'ok',
                'solicitud' => $solicitudes
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

    // crear solicitud por el profesor logueado
    public function requestAPI(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user || !$user->profesor) {
                return response()->json([
                    'response' => 401,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No autenticado o el usuario no es profesor.'
                ], 401);
            }
            $idProfesor = $user->profesor->id;

            $data = $request->validate([
                'id_oferta' => 'required|integer|min:1|exists:oferta,id',
                'id_empresa' => 'required|integer|min:1|exists:empresa,id',
                'alumnos' => 'required|array|min:1',
                'alumnos.*' => 'required|integer|min:1|distinct|exists:alumno,id'
            ], [
                'id_oferta.required' => 'La oferta es obligatoria.',
                'id_oferta.exists' => 'La oferta no existe.',
                'id_oferta.integer' => 'El identificador de la oferta debe ser un número entero.',
                'id_empresa.required' => 'La empresa es obligatoria.',
                'id_empresa.exists' => 'La empresa no existe.',
                'id_empresa.integer' => 'El identificador de la empresa debe ser un número entero.',
                'alumnos.array' => 'El campo alumnos debe ser un array.',
                'alumnos.min' => 'Debes seleccionar al menos un alumno.',
                'alumnos.*.required' => 'El alumno es obligatorio.',
                'alumnos.*.integer' => 'El identificador del alumno debe ser un número entero.',
                'alumnos.*.min' => 'El identificador del alumno debe ser mayor que 0.',
                'alumnos.*.distinct' => 'No puedes seleccionar el mismo alumno más de una vez.',
                'alumnos.*.exists' => 'El alumno seleccionado no existe.',
            ]);

            $alumnosAsignados = Alumno::where('id_profesor', $idProfesor)->pluck('id')->toArray();
            $alumnosNoValidos = array_diff($data['alumnos'], $alumnosAsignados);
            if (count($alumnosNoValidos) > 0) {
                return response()->json([
                    'response' => 400,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'Los siguientes alumnos no están asignados a este profesor: '
                ], 400);
            }

            $yaExisten = [];
            foreach ($data['alumnos'] as $idAlumno) {
                $existe = Solicitud::where('id_oferta', $data['id_oferta'])
                    ->where('id_alumno', $idAlumno)
                    ->where('id_profesor', $idProfesor)
                    ->exists();
                if ($existe) {
                    $yaExisten[] = $idAlumno;
                }
            }
            if (count($yaExisten) > 0) {
                return response()->json([
                    'response' => 400,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'Ya existe una solicitud previa.'
                ], 400);
            }

            $response = DB::transaction(function () use ($data, $idProfesor) {
                foreach ($data['alumnos'] as $idAlumno) {
                    $solicitud = Solicitud::create([
                        'id_oferta' => $data['id_oferta'],
                        'id_alumno' => $idAlumno,
                        'id_empresa' => $data['id_empresa'],
                        'id_profesor' => $idProfesor
                    ]);

                    $alumno = Alumno::find($idAlumno);
                    if ($alumno && $alumno->usuario) {
                        $alumno->usuario->notify(new Notificacion(
                            'Tienes una nueva solicitud.',
                            ['solicitud_id' => $solicitud->id]
                        ));
                    }

                    $profesor = Profesor::find($idProfesor);
                    if ($profesor && $profesor->usuario) {
                        $profesor->usuario->notify(new Notificacion(
                            'Has enviado una nueva solicitud.',
                            ['solicitud_id' => $solicitud->id]
                        ));
                    }

                    $empresa = Empresa::find($data['id_empresa']);
                    if ($empresa && $empresa->usuario) {
                        $empresa->usuario->notify(new Notificacion(
                            'Tu empresa ha recibido una nueva solicitud.',
                            ['solicitud_id' => $solicitud->id]
                        ));
                    }
                }
                return response()->json([
                    'response' => 201,
                    'success' => true,
                    'status' => 'ok',
                    'message' => 'Solicitud enviada correctamente.'
                ], 201);
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

    // actualizar solicitud por la empresa logueada
    public function updateRequestAPI(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $empresa = $user->empresa;

            if (!$empresa) {
                return response()->json([
                    'response' => 401,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No autenticado o el usuario no es una empresa.'
                ], 401);
            }

            $solicitud = Solicitud::with('oferta')->find($id);

            if (!$solicitud || !$solicitud->oferta || $solicitud->oferta->eliminado != 0) {
                return response()->json([
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'La solicitud no existe o la oferta está eliminada.'
                ], 404);
            }

            $data = $request->validate([
                'estado' => 'required|in:Pendiente,Revision,Aceptada,Rechazada'
            ], [
                'estado.required' => 'El estado es obligatorio.',
                'estado.in' => 'El estado debe ser "Pendiente", "Revision", "Aceptada" o "Rechazada".'
            ]);

            $solicitud->update($data);

            $alumno = $solicitud->alumno;
            $profesor = $solicitud->profesor;
            $empresa = $solicitud->oferta ? $solicitud->oferta->empresa : null;

            if ($alumno && $alumno->usuario) {
                $alumno->usuario->notify(new Notificacion(
                    'El estado de tu solicitud ha cambiado a: ' . $data['estado'],
                    ['solicitud_id' => $solicitud->id]
                ));
            }
            if ($profesor && $profesor->usuario) {
                $profesor->usuario->notify(new Notificacion(
                    'El estado de una solicitud ha cambiado a: ' . $data['estado'],
                    ['solicitud_id' => $solicitud->id]
                ));
            }
            if ($empresa && $empresa->usuario) {
                $empresa->usuario->notify(new Notificacion(
                    'El estado de una solicitud de tu empresa ha cambiado a: ' . $data['estado'],
                    ['solicitud_id' => $solicitud->id]
                ));
            }

            $response = [
                'response' => 200,
                'success' => true,
                'status' => 'ok',
                'message' => 'La solicitud se ha actualizado correctamente.'
            ];
            return response()->json($response, 200);

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

    // listar empresas asignadas a los alumnos por el profesor logueado
    public function listCommpanyAssignedToStudentByTeacherAPI($idAlumno)
    {
        try {
            $user = Auth::user();

            if (!$user || !$user->profesor) {
                return response()->json([
                    'response' => 401,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No autenticado o el usuario no es profesor.'
                ], 401);
            }
            $idProfesor = $user->profesor->id;

            $solicitudes = Solicitud::with(['empresa.usuario'])
                ->where('id_profesor', $idProfesor)
                ->where('id_alumno', $idAlumno)
                ->where('eliminado', 0)
                ->where('estado', 'Aceptada')
                ->get();

            if ($solicitudes->isEmpty()) {
                $response = [
                    'response' => 404,
                    'success' => false,
                    'status' => 'error',
                    'message' => 'No se encontraron empresas asignadas a los alumnos.'
                ];
                return response()->json($response, 404);
            }

            // recuperar las empresas asignadas por el alumno
            $empresas = [];
            foreach ($solicitudes as $solicitud) {
                if ($solicitud->empresa) {
                    $empresaArr = $solicitud->empresa->toArray();
                    $empresaArr['id_alumno'] = $solicitud->id_alumno;
                    $empresas[] = $empresaArr;
                }
            }

            $response = [
                'response' => 200,
                'success' => true,
                'status' => 'ok',
                'empresas' => $empresas
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
