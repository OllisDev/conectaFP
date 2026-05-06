<?php

namespace Tests\Unit;

use App\Models\Tutoria;
use App\Models\Alumno;
use App\Models\Profesor;
use App\Models\Empresa;
use App\Models\Usuario;

class TutoriaApiTest extends ApiTestCase
{
    public function test_student_can_list_their_tutorials()
    {
        $usuario = $this->authenticateUser('alumno');
        $alumno = $usuario->alumno;

        // Crear tutorías para este alumno
        $tutoria1 = Tutoria::factory()->create(['id_alumno' => $alumno->id]);
        $tutoria2 = Tutoria::factory()->create(['id_alumno' => $alumno->id]);

        // Tutoría de otro alumno (no debe aparecer)
        Tutoria::factory()->create();

        // CORREGIDO: Usar método autenticado
        $response = $this->getJsonAuthenticated('/api/tutoria/alumno', $usuario);

        $this->assertApiResponse($response, 200);
        $response->assertJsonStructure([
            'response',
            'success',
            'status',
            'message',
            'Tutorias' => [
                '*' => ['id', 'id_alumno', 'id_profesor', 'id_empresa', 'fecha_inicio', 'fecha_fin', 'estado']
            ]
        ]);
        $response->assertJsonCount(3, 'Tutorias');
    }

    public function test_student_returns_404_when_no_tutorials_exist()
    {
        $usuario = $this->authenticateUser('alumno');

        // CORREGIDO: Usar método autenticado
        $response = $this->getJsonAuthenticated('/api/tutoria/alumno', $usuario);

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'success' => false,
            'message' => 'No existe ninguna tutoria de ese alumno.'
        ]);
    }

    public function test_non_student_cannot_list_student_tutorials()
    {
        $this->authenticateUser('profesor'); // Usuario que no es alumno

        $response = $this->getJson('/api/tutoria/alumno');

        $response->assertStatus(401);
        // CORREGIDO: Mensaje estándar de Laravel
        $response->assertJsonFragment([
            'message' => 'Unauthenticated.'
        ]);
    }

    public function test_teacher_can_list_their_tutorials()
    {
        $usuario = $this->authenticateUser('profesor');
        $profesor = $usuario->profesor;

        // Crear tutorías gestionadas por este profesor
        $tutoria1 = Tutoria::factory()->create(['id_profesor' => $profesor->id]);
        $tutoria2 = Tutoria::factory()->create(['id_profesor' => $profesor->id]);

        // Tutoría de otro profesor (no debe aparecer)
        Tutoria::factory()->create();

        // CORREGIDO: Usar método autenticado
        $response = $this->getJsonAuthenticated('/api/tutoria/profesor', $usuario);

        $this->assertApiResponse($response, 200);
        $response->assertJsonStructure([
            'response',
            'success',
            'status',
            'message',
            'Tutorias' => [
                '*' => ['id', 'id_alumno', 'id_profesor', 'id_empresa', 'fecha_inicio', 'fecha_fin', 'estado']
            ]
        ]);
        $response->assertJsonCount(3, 'Tutorias');
    }

    public function test_teacher_returns_404_when_no_tutorials_exist()
    {
        $usuario = $this->authenticateUser('profesor');

        // CORREGIDO: Usar método autenticado
        $response = $this->getJsonAuthenticated('/api/tutoria/profesor', $usuario);

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'success' => false,
            'message' => 'No existe ninguna tutoria que gestione ese profesor.'
        ]);
    }

    public function test_non_teacher_cannot_list_teacher_tutorials()
    {
        $this->authenticateUser('alumno'); // Usuario que no es profesor

        $response = $this->getJson('/api/tutoria/profesor');

        $response->assertStatus(401);
        // CORREGIDO: Mensaje estándar de Laravel
        $response->assertJsonFragment([
            'message' => 'Unauthenticated.'
        ]);
    }

    public function test_teacher_can_create_tutorial()
    {
        $usuario = $this->authenticateUser('profesor');
        $profesor = $usuario->profesor;

        $alumno = Alumno::factory()->create();
        $empresa = Empresa::factory()->create();

        $tutorialData = [
            'id_alumno' => $alumno->id,
            'id_empresa' => $empresa->id,
            'fecha_inicio' => now()->addDays(1)->format('Y-m-d H:i:s'),
            'fecha_fin' => now()->addDays(30)->format('Y-m-d H:i:s'),
            'estado' => 'Activa'
        ];

        // CORREGIDO: Usar método autenticado
        $response = $this->postJsonAuthenticated('/api/tutoria/crear', $tutorialData, $usuario);

        $response->assertStatus(201);
        $this->assertApiResponse($response, 201);
        $response->assertJsonFragment([
            'message' => 'Se ha creado la tutoría correctamente.'
        ]);

        $this->assertDatabaseHas('tutoria', [
            'id_profesor' => $profesor->id,
            'id_alumno' => $alumno->id,
            'id_empresa' => $empresa->id,
            'estado' => 'Activa'
        ]);
    }

    public function test_non_teacher_cannot_create_tutorial()
    {
        $this->authenticateUser('alumno'); // Usuario que no es profesor

        $tutorialData = [
            'id_alumno' => 1,
            'id_empresa' => 1,
            'fecha_inicio' => now()->addDays(1)->format('Y-m-d H:i:s'),
            'estado' => 'Activa'
        ];

        $response = $this->postJson('/api/tutoria/crear', $tutorialData);

        $response->assertStatus(401);
        // CORREGIDO: Mensaje estándar de Laravel
        $response->assertJsonFragment([
            'message' => 'Unauthenticated.'
        ]);
    }

    public function test_tutorial_creation_validates_required_fields()
    {
        $usuario = $this->authenticateUser('profesor');

        // CORREGIDO: Usar método autenticado
        $response = $this->postJsonAuthenticated('/api/tutoria/crear', [
            'id_alumno' => 1
            // Faltan campos requeridos
        ], $usuario);

        $response->assertStatus(400);
    }

    public function test_tutorial_creation_validates_start_date_not_in_past()
    {
        $usuario = $this->authenticateUser('profesor');

        $alumno = Alumno::factory()->create();
        $empresa = Empresa::factory()->create();

        $tutorialData = [
            'id_alumno' => $alumno->id,
            'id_empresa' => $empresa->id,
            'fecha_inicio' => now()->subDays(1)->format('Y-m-d H:i:s'), // Fecha en el pasado
            'estado' => 'Activa'
        ];

        // CORREGIDO: Usar método autenticado
        $response = $this->postJsonAuthenticated('/api/tutoria/crear', $tutorialData, $usuario);

        $response->assertStatus(400);
    }

    public function test_tutorial_creation_validates_end_date_after_start_date()
    {
        $usuario = $this->authenticateUser('profesor');

        $alumno = Alumno::factory()->create();
        $empresa = Empresa::factory()->create();

        $tutorialData = [
            'id_alumno' => $alumno->id,
            'id_empresa' => $empresa->id,
            'fecha_inicio' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'fecha_fin' => now()->addDays(1)->format('Y-m-d H:i:s'), // Fecha fin antes que inicio
            'estado' => 'Activa'
        ];

        // CORREGIDO: Usar método autenticado
        $response = $this->postJsonAuthenticated('/api/tutoria/crear', $tutorialData, $usuario);

        $response->assertStatus(400);
    }

    public function test_tutorial_creation_validates_valid_estados()
    {
        $usuario = $this->authenticateUser('profesor');

        $alumno = Alumno::factory()->create();
        $empresa = Empresa::factory()->create();

        $tutorialData = [
            'id_alumno' => $alumno->id,
            'id_empresa' => $empresa->id,
            'fecha_inicio' => now()->addDays(1)->format('Y-m-d H:i:s'),
            'estado' => 'EstadoInvalido'
        ];

        // CORREGIDO: Usar método autenticado
        $response = $this->postJsonAuthenticated('/api/tutoria/crear', $tutorialData, $usuario);

        $response->assertStatus(400);
    }

    public function test_can_update_tutorial()
    {
        $tutoria = Tutoria::factory()->create([
            'estado' => 'Activa'
        ]);

        $updateData = [
            'estado' => 'Finalizada'
        ];

        $response = $this->putJson("/api/tutoria/{$tutoria->id}/actualizar", $updateData);

        $this->assertApiResponse($response, 200);
        $response->assertJsonFragment([
            'message' => 'La tutoria se ha actualizado correctamente.'
        ]);

        $this->assertDatabaseHas('tutoria', [
            'id' => $tutoria->id,
            'estado' => 'Finalizada'
        ]);
    }

    public function test_returns_404_when_updating_nonexistent_tutorial()
    {
        $updateData = [
            'estado' => 'Finalizada'
        ];

        $response = $this->putJson('/api/tutoria/999/actualizar', $updateData);

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'message' => 'La tutoría no existe.'
        ]);
    }

    public function test_tutorial_update_validates_valid_estados()
    {
        $tutoria = Tutoria::factory()->create();

        $updateData = [
            'estado' => 'EstadoInvalido'
        ];

        $response = $this->putJson("/api/tutoria/{$tutoria->id}/actualizar", $updateData);

        $response->assertStatus(400);
    }

    public function test_can_delete_tutorial()
    {
        $tutoria = Tutoria::factory()->create();

        $response = $this->deleteJson("/api/tutoria/{$tutoria->id}/eliminar");

        $this->assertApiResponse($response, 200);
        $response->assertJsonFragment([
            'message' => 'La tutoria ha sido eliminado correctamente.'
        ]);

        $this->assertDatabaseMissing('tutoria', [
            'id' => $tutoria->id
        ]);
    }

    public function test_returns_404_when_deleting_nonexistent_tutorial()
    {
        $response = $this->deleteJson('/api/tutoria/999/eliminar');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'message' => 'No existe la tutoría.'
        ]);
    }
}