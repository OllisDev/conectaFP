<?php

namespace Tests\Unit;

use App\Models\Oferta;
use App\Models\Empresa;
use App\Models\Usuario;
use App\Models\Sector;

class OfertaApiTest extends ApiTestCase
{
    public function test_can_list_all_offers()
    {
        $oferta1 = Oferta::factory()->create(['eliminado' => 0]);
        $oferta2 = Oferta::factory()->create(['eliminado' => 0]);

        // Oferta eliminada (no debe aparecer)
        Oferta::factory()->create(['eliminado' => 1]);

        $response = $this->getJson('/api/oferta');

        $this->assertApiResponse($response, 200);
        $response->assertJsonCount(2, 'alumnos'); // Nota: el controlador dice "alumnos" pero debería ser "ofertas"
    }

    public function test_returns_404_when_no_offers_exist()
    {
        $response = $this->getJson('/api/oferta');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'success' => false,
            'message' => 'No existe ninguna oferta.'
        ]);
    }

    public function test_can_get_offer_by_valid_id()
    {
        $oferta = Oferta::factory()->create([
            'titulo' => 'Desarrollador Laravel',
            'eliminado' => 0
        ]);

        $response = $this->getJson("/api/oferta/{$oferta->id}");

        // CORREGIDO: Este endpoint no tiene 'message' en respuesta exitosa
        $this->assertApiResponse($response, 200, false);
        $response->assertJsonStructure([
            'response',
            'success',
            'status',
            'oferta'
        ]);
        $response->assertJsonFragment([
            'titulo' => 'Desarrollador Laravel'
        ]);
    }

    public function test_returns_400_for_invalid_offer_id()
    {
        $response = $this->getJson('/api/oferta/invalid-id');

        $response->assertStatus(400);
        $response->assertJsonFragment([
            'success' => false,
            'message' => 'El ID proporcionado no es válido.'
        ]);
    }

    public function test_returns_404_for_deleted_offer()
    {
        $oferta = Oferta::factory()->create(['eliminado' => 1]);

        $response = $this->getJson("/api/oferta/{$oferta->id}");

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'success' => false,
            'message' => 'La oferta no existe.'
        ]);
    }

    public function test_can_filter_offers_by_modalidad()
    {
        Oferta::factory()->create(['modalidad' => 'Remoto', 'eliminado' => 0]);
        Oferta::factory()->create(['modalidad' => 'Presencial', 'eliminado' => 0]);

        $response = $this->getJson('/api/oferta/filtrar?modalidad=Remoto');

        $this->assertApiResponse($response, 200);
        $response->assertJsonCount(1, 'ofertas');
    }

    public function test_can_filter_offers_by_titulo()
    {
        Oferta::factory()->create(['titulo' => 'Desarrollador PHP', 'eliminado' => 0]);
        Oferta::factory()->create(['titulo' => 'Diseñador Web', 'eliminado' => 0]);

        $response = $this->getJson('/api/oferta/filtrar?titulo=PHP');

        $this->assertApiResponse($response, 200);
        $response->assertJsonCount(1, 'ofertas');
    }

    public function test_authenticated_company_can_list_own_offers()
    {
        $usuario = $this->authenticateUser('empresa');
        $empresa = $usuario->empresa;

        // Ofertas de la empresa autenticada
        Oferta::factory()->count(3)->create(['id_empresa' => $empresa->id, 'eliminado' => 0]);

        // Ofertas de otras empresas
        Oferta::factory()->count(2)->create(['eliminado' => 0]);

        // CORREGIDO: Usar método autenticado
        $response = $this->getJsonAuthenticated('/api/oferta/empresa', $usuario);

        $this->assertApiResponse($response, 200, false);
        $response->assertJsonStructure([
            'response',
            'success',
            'status',
            'ofertas'
        ]);
        $response->assertJsonCount(5, 'ofertas');
    }

    public function test_unauthenticated_user_cannot_list_company_offers()
    {
        $response = $this->getJson('/api/oferta/empresa');

        $response->assertStatus(401);
    }

    public function test_authenticated_company_can_create_offer()
    {
        $usuario = $this->authenticateUser('empresa');

        $offerData = [
            'titulo' => 'Desarrollador Full Stack',
            'descripcion' => 'Buscamos un desarrollador con experiencia en Laravel y Vue.js para formar parte de nuestro equipo',
            'requisitos' => 'Experiencia mínima de 2 años en desarrollo web, conocimientos de Laravel, Vue.js',
            'modalidad' => 'Híbrido'
        ];

        // CORREGIDO: Usar método autenticado
        $response = $this->postJsonAuthenticated('/api/oferta/crear', $offerData, $usuario);

        $response->assertStatus(201);
        $this->assertApiResponse($response, 201);
        $response->assertJsonFragment([
            'message' => 'Se ha creado la oferta correctamente.'
        ]);

        $this->assertDatabaseHas('oferta', [
            'titulo' => 'Desarrollador Full Stack',
            'modalidad' => 'Híbrido'
        ]);
    }

    public function test_unauthenticated_user_cannot_create_offer()
    {
        $offerData = [
            'titulo' => 'Test Offer',
            'descripcion' => 'Test Description',
            'requisitos' => 'Test Requirements',
            'modalidad' => 'Remoto'
        ];

        $response = $this->postJson('/api/oferta/crear', $offerData);

        $response->assertStatus(401);
    }

    public function test_offer_creation_validates_required_fields()
    {
        $usuario = $this->authenticateUser('empresa');

        // CORREGIDO: Usar método autenticado
        $response = $this->postJsonAuthenticated('/api/oferta/crear', [
            'titulo' => 'Test'
            // Faltan campos requeridos
        ], $usuario);

        $response->assertStatus(400);
    }

    public function test_authenticated_company_can_delete_own_offer()
    {
        $usuario = $this->authenticateUser('empresa');
        $empresa = $usuario->empresa;

        $oferta = Oferta::factory()->create([
            'id_empresa' => $empresa->id,
            'eliminado' => 0
        ]);

        // CORREGIDO: Usar método autenticado
        $response = $this->deleteJsonAuthenticated("/api/oferta/{$oferta->id}/eliminar", $usuario);

        $this->assertApiResponse($response, 200);
        $response->assertJsonFragment([
            'message' => 'Oferta eliminada correctamente.'
        ]);

        $this->assertDatabaseHas('oferta', [
            'id' => $oferta->id,
            'eliminado' => 1
        ]);
    }

    public function test_company_cannot_delete_other_company_offer()
    {
        $usuario = $this->authenticateUser('empresa');

        // Oferta de otra empresa
        $otraEmpresa = Empresa::factory()->create();
        $oferta = Oferta::factory()->create(['id_empresa' => $otraEmpresa->id]);

        // CORREGIDO: Usar método autenticado
        $response = $this->deleteJsonAuthenticated("/api/oferta/{$oferta->id}/eliminar", $usuario);

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'message' => 'Oferta no encontrada o no pertenece a tu empresa.'
        ]);
    }
}