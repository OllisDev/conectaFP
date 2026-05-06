<?php

namespace Tests\Unit;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UsuarioApiTest extends ApiTestCase
{
    use RefreshDatabase;

    public function test_can_list_all_users()
    {
        // Crear usuarios de prueba
        Usuario::factory()->count(3)->create();

        $response = $this->getJson('/api/usuario');

        $this->assertApiResponse($response, 200);
        $response->assertJsonStructure([
            'response',
            'success',
            'status',
            'message',
            'usuarios' => [
                '*' => ['id', 'nombre', 'apellidos', 'email', 'activo', 'fecha_registro']
            ]
        ]);
        $response->assertJsonCount(3, 'usuarios');
    }

    public function test_can_get_user_by_id()
    {
        $usuario = Usuario::factory()->create([
            'nombre' => 'Test User',
            'email' => 'test@conectafp.com'
        ]);

        $response = $this->getJson("/api/usuario/{$usuario->id}");

        $this->assertApiResponse($response, 200, false);
        $response->assertJsonStructure([
            'response',
            'success',
            'status',
            'usuario'
        ]);
        $response->assertJsonFragment([
            'nombre' => 'Test User',
            'email' => 'test@conectafp.com'
        ]);
    }

    public function test_user_login_with_valid_credentials()
    {
        $usuario = Usuario::factory()->create([
            'email' => 'test@example.com',
            'contrasena' => Hash::make('Password123!')
        ]);

        $response = $this->postJson('/api/usuario/login', [
            'email' => 'test@example.com',
            'contrasena' => 'Password123!'
        ]);

        $this->assertApiResponse($response, 200);
        $response->assertJsonStructure([
            'response',
            'success',
            'status',
            'message',
            'api_token', // CORREGIDO: Es 'api_token' no 'token'
            'nombre',
            'id_rol',
            'rol'
        ]);
    }

    public function test_user_login_with_invalid_credentials()
    {
        $response = $this->postJson('/api/usuario/login', [
            'email' => 'wrong@example.com',
            'contrasena' => 'wrongpassword'
        ]);

        $response->assertStatus(401);
        $response->assertJsonFragment([
            'success' => false
        ]);
    }
}