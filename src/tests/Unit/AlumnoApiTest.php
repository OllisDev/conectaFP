<?php

namespace Tests\Unit;

use App\Models\Alumno;
use App\Models\Usuario;
use App\Models\Profesor;
use App\Models\Grado;
use App\Models\CentroEducativo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AlumnoApiTest extends ApiTestCase
{
    public function test_can_list_all_students()
    {
        $alumno1 = Alumno::factory()->create();
        $alumno2 = Alumno::factory()->create();

        $response = $this->getJson('/api/alumno');

        $this->assertApiResponse($response, 200);
        $response->assertJsonStructure([
            'response',
            'success',
            'status',
            'message',
            'alumnos' => [
                '*' => ['id', 'id_usuario', 'id_profesor', 'id_centro', 'id_grado', 'curso', 'dni', 'cv', 'disponibilidad']
            ]
        ]);
        $response->assertJsonCount(2, 'alumnos');
    }

    public function test_returns_404_when_no_students_exist()
    {
        $response = $this->getJson('/api/alumno');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'success' => false,
            'message' => 'No existe ningún alumno.'
        ]);
    }

    public function test_can_get_student_by_valid_id()
    {
        $alumno = Alumno::factory()->create([
            'dni' => '12345678A',
            'curso' => '2º'
        ]);

        $response = $this->getJson("/api/alumno/{$alumno->id}");


        $this->assertApiResponse($response, 200, false);
        $response->assertJsonStructure([
            'response',
            'success',
            'status',
            'alumno'
        ]);
        $response->assertJsonFragment([
            'dni' => '12345678A',
            'curso' => '2º'
        ]);
    }

    public function test_returns_400_for_invalid_student_id()
    {
        $response = $this->getJson('/api/alumno/invalid-id');

        $response->assertStatus(400);
        $response->assertJsonFragment([
            'success' => false,
            'message' => 'El ID proporcionado no es válido.'
        ]);
    }

    public function test_teacher_can_list_assigned_students()
    {
        $usuario = $this->authenticateUser('profesor');
        $profesor = $usuario->profesor;


        $alumno1 = Alumno::factory()->create(['id_profesor' => $profesor->id]);
        $alumno2 = Alumno::factory()->create(['id_profesor' => $profesor->id]);


        Alumno::factory()->create();

        $response = $this->getJsonAuthenticated('/api/alumnos/profesor', $usuario);

        $this->assertApiResponse($response, 200, false);
        $response->assertJsonStructure([
            'response',
            'success',
            'status',
            'alumnos' => [
                '*' => ['id', 'id_usuario', 'id_centro', 'id_grado', 'curso', 'dni', 'cv', 'disponibilidad']
            ]
        ]);
        $response->assertJsonCount(2, 'alumnos');
    }

    public function test_unauthenticated_user_cannot_list_assigned_students()
    {
        $response = $this->getJson('/api/alumnos/profesor');

        $response->assertStatus(401);
    }

    public function test_non_teacher_cannot_list_assigned_students()
    {
        $usuario = $this->authenticateUser('alumno');

        $response = $this->getJsonAuthenticated('/api/alumnos/profesor', $usuario);

        $response->assertStatus(401);
        $response->assertJsonFragment([
            'message' => 'No autenticado o el usuario no es profesor.'
        ]);
    }


    public function test_student_registration_validates_required_fields()
    {
        $response = $this->postJson('/api/alumno/register', [
            'nombre' => 'Juan'
        ]);

        $response->assertStatus(400);
        $response->assertJsonStructure([
            'response',
            'success',
            'status',
            'message'
        ]);
    }

    public function test_student_registration_validates_unique_email()
    {
        $existingUser = Usuario::factory()->create(['email' => 'existing@test.com']);

        $response = $this->postJson('/api/alumno/register', [
            'nombre' => 'Juan',
            'apellidos' => 'Pérez',
            'email' => 'existing@test.com',
            'contrasena' => 'Password123!',
            'telefono' => '612345678'
        ]);

        $response->assertStatus(400);
    }
}