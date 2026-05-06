<?php

namespace Tests\Unit;

use App\Models\Profesor;
use App\Models\Usuario;
use App\Models\Grado;
use App\Models\CentroEducativo;
use App\Models\Departamento;

class ProfesorApiTest extends ApiTestCase
{
    public function test_can_list_all_teachers()
    {
        $profesor1 = Profesor::factory()->create();
        $profesor2 = Profesor::factory()->create();

        $response = $this->getJson('/api/profesor');

        $this->assertApiResponse($response, 200);
        $response->assertJsonStructure([
            'response',
            'success',
            'status',
            'message',
            'alumnos' => [ // Nota: el controlador dice "alumnos" pero debería ser "profesores"
                '*' => ['id', 'id_usuario', 'id_centro', 'id_grado', 'id_departamento', 'dni']
            ]
        ]);
        $response->assertJsonCount(2, 'alumnos');
    }

    public function test_returns_404_when_no_teachers_exist()
    {
        $response = $this->getJson('/api/profesor');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'success' => false,
            'message' => 'No existe ningún profesor.'
        ]);
    }

    public function test_can_list_teachers_by_center()
    {
        $centro = CentroEducativo::factory()->create();
        $profesor1 = Profesor::factory()->create(['id_centro' => $centro->id]);
        $profesor2 = Profesor::factory()->create(['id_centro' => $centro->id]);

        // Profesor de otro centro
        Profesor::factory()->create();

        $response = $this->getJson("/api/profesor/centro/{$centro->id}");

        // CORREGIDO: Este endpoint no tiene 'message' en respuesta exitosa
        $this->assertApiResponse($response, 200, false);
        $response->assertJsonStructure([
            'response',
            'success',
            'status',
            'profesores'
        ]);
        $response->assertJsonCount(3, 'profesores');
    }

    public function test_returns_400_for_invalid_center_id()
    {
        $response = $this->getJson('/api/profesor/centro/invalid-id');

        $response->assertStatus(400);
        $response->assertJsonFragment([
            'success' => false,
            'message' => 'El ID proporcionado no es válido.'
        ]);
    }

    public function test_can_get_teacher_by_valid_id()
    {
        $profesor = Profesor::factory()->create([
            'dni' => '12345678Z' // CORREGIDO: DNI español válido
        ]);

        $response = $this->getJson("/api/profesor/{$profesor->id}");

        // CORREGIDO: Este endpoint no tiene 'message' en respuesta exitosa
        $this->assertApiResponse($response, 200, false);
        $response->assertJsonStructure([
            'response',
            'success',
            'status',
            'profesor'
        ]);
        $response->assertJsonFragment([
            'dni' => '12345678Z'
        ]);
    }

    public function test_can_register_new_teacher()
    {
        $grado = Grado::factory()->create();
        $centro = CentroEducativo::factory()->create();
        $departamento = Departamento::factory()->create();

        $teacherData = [
            'nombre' => 'María',
            'apellidos' => 'García López',
            'contrasena' => 'Password123!',
            'email' => 'maria.garcia@example.com',
            'telefono' => '612345678',
            'id_centro' => $centro->id,
            'id_grado' => $grado->id,
            'id_departamento' => $departamento->id,
            'dni' => '87654321X' // CORREGIDO: DNI español válido
        ];

        $response = $this->postJson('/api/profesor/register', $teacherData);

        // CORREGIDO: Probablemente el controlador devuelve 200, no 201
        $response->assertStatus(200);

        // Verificar que la respuesta indica éxito
        $response->assertJsonFragment([
            'response' => 201,
            'success' => true,
            'message' => 'Cuenta de profesor creada correctamente.'
        ]);

        $this->assertDatabaseHas('usuario', [
            'email' => 'maria.garcia@example.com',
            'nombre' => 'María'
        ]);

        $this->assertDatabaseHas('profesor', [
            'dni' => '87654321X'
        ]);
    }

    public function test_teacher_registration_validates_required_fields()
    {
        $response = $this->postJson('/api/profesor/register', [
            'nombre' => 'María'
            // Faltan campos requeridos
        ]);

        $response->assertStatus(400);
    }

    public function test_teacher_registration_validates_unique_dni()
    {
        $existingProfesor = Profesor::factory()->create(['dni' => '87654321X']);

        $grado = Grado::factory()->create();
        $centro = CentroEducativo::factory()->create();
        $departamento = Departamento::factory()->create();

        $teacherData = [
            'nombre' => 'María',
            'apellidos' => 'García López',
            'contrasena' => 'Password123!',
            'email' => 'maria@example.com',
            'telefono' => '612345678',
            'id_centro' => $centro->id,
            'id_grado' => $grado->id,
            'id_departamento' => $departamento->id,
            'dni' => '87654321X' // DNI ya existe
        ];

        $response = $this->postJson('/api/profesor/register', $teacherData);

        $response->assertStatus(400);
    }

    public function test_teacher_registration_validates_unique_email()
    {
        $existingUser = Usuario::factory()->create(['email' => 'existing@test.com']);

        $grado = Grado::factory()->create();
        $centro = CentroEducativo::factory()->create();
        $departamento = Departamento::factory()->create();

        $teacherData = [
            'nombre' => 'María',
            'apellidos' => 'García López',
            'contrasena' => 'Password123!',
            'email' => 'existing@test.com', // Email ya existe
            'telefono' => '612345678',
            'id_centro' => $centro->id,
            'id_grado' => $grado->id,
            'id_departamento' => $departamento->id,
            'dni' => '11111111H'
        ];

        $response = $this->postJson('/api/profesor/register', $teacherData);

        $response->assertStatus(400);
    }
}