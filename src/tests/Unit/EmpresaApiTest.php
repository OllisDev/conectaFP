<?php

namespace Tests\Unit;

use App\Models\Empresa;
use App\Models\Usuario;
use App\Models\Sector;

class EmpresaApiTest extends ApiTestCase
{
    public function test_can_list_all_companies()
    {
        // Crear empresas con usuarios y sectores
        $sector1 = Sector::factory()->create();
        $sector2 = Sector::factory()->create();

        $empresa1 = Empresa::factory()->create(['id_sector' => $sector1->id]);
        $empresa2 = Empresa::factory()->create(['id_sector' => $sector2->id]);

        $response = $this->getJson('/api/empresa');

        $this->assertApiResponse($response, 200);
        $response->assertJsonStructure([
            'response',
            'success',
            'status',
            'message',
            'empresas'
        ]);
        $response->assertJsonCount(2, 'empresas');
    }

    public function test_returns_404_when_no_companies_exist()
    {
        $response = $this->getJson('/api/empresa');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'success' => false,
            'message' => 'No existe ningún alumno.' // Error en el controlador
        ]);
    }

    public function test_can_get_company_by_valid_id()
    {
        $sector = Sector::factory()->create();
        $empresa = Empresa::factory()->create([
            'nif' => 'A12345678',
            'descripcion' => 'Test Company',
            'id_sector' => $sector->id
        ]);

        $response = $this->getJson("/api/empresa/{$empresa->id}");

        // Este endpoint no tiene 'message' en respuesta exitosa
        $this->assertApiResponse($response, 200, false);
        $response->assertJsonStructure([
            'response',
            'success',
            'status',
            'empresa'
        ]);
    }

    public function test_returns_400_for_invalid_company_id()
    {
        $response = $this->getJson('/api/empresa/invalid-id');

        $response->assertStatus(400);
        $response->assertJsonFragment([
            'success' => false,
            'message' => 'El ID proporcionado no es válido.'
        ]);
    }

    public function test_can_register_new_company()
    {
        $sector = Sector::factory()->create();

        $companyData = [
            'nombre' => 'TechCorp SA',
            'contrasena' => 'Password123!',
            'email' => 'contact@techcorp.com',
            'telefono' => '612345678',
            'id_sector' => $sector->id,
            'nif' => '12345678Z',
            'descripcion' => 'Empresa de tecnología especializada en desarrollo web',
            'direccion' => 'Calle Mayor 123, Madrid',
            'web' => 'https://www.techcorp.com'
        ];

        $response = $this->postJson('/api/empresa/register', $companyData);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'response' => 201,
            'success' => true,
            'message' => 'Cuenta de empresa creada correctamente.'
        ]);

        $this->assertDatabaseHas('usuario', [
            'email' => 'contact@techcorp.com',
            'nombre' => 'TechCorp SA'
        ]);

        $this->assertDatabaseHas('empresa', [
            'nif' => '12345678Z'
        ]);
    }

    public function test_company_registration_validates_required_fields()
    {
        $response = $this->postJson('/api/empresa/register', [
            'nombre' => 'Test Company'
            // Faltan campos requeridos
        ]);

        $response->assertStatus(400);
        $response->assertJsonStructure([
            'response',
            'success',
            'status',
            'message'
        ]);
    }

    public function test_company_registration_validates_unique_email()
    {
        $existingUser = Usuario::factory()->create(['email' => 'existing@test.com']);
        $sector = Sector::factory()->create();

        $companyData = [
            'nombre' => 'Test Company',
            'contrasena' => 'Password123!',
            'email' => 'existing@test.com', // Email ya existe
            'telefono' => '612345678',
            'id_sector' => $sector->id,
            'nif' => 'A12345678',
            'direccion' => 'Test Address'
        ];

        $response = $this->postJson('/api/empresa/register', $companyData);

        $response->assertStatus(400);
    }
}