<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class ApiTestCase extends TestCase
{
    use RefreshDatabase;

    protected function authenticateUser($userType = 'alumno')
    {
        $usuario = Usuario::factory()->create([
            'api_token' => \Illuminate\Support\Str::random(60) // Generar token API
        ]);

        // Crear el tipo específico de usuario
        if ($userType === 'alumno') {
            \App\Models\Alumno::factory()->create(['id_usuario' => $usuario->id]);
        } elseif ($userType === 'profesor') {
            \App\Models\Profesor::factory()->create(['id_usuario' => $usuario->id]);
        } elseif ($userType === 'empresa') {
            \App\Models\Empresa::factory()->create(['id_usuario' => $usuario->id]);
        }

        return $usuario;
    }

    protected function assertApiResponse($response, $expectedStatus = 200, $checkMessage = true)
    {
        $response->assertStatus($expectedStatus);

        if ($checkMessage) {
            $response->assertJsonStructure([
                'response',
                'success',
                'message'
            ]);
        } else {
            $response->assertJsonStructure([
                'response',
                'success'
            ]);
        }
    }

    protected function getJsonAuthenticated($uri, $user = null, $headers = [])
    {
        if ($user) {
            $headers['Authorization'] = 'Bearer ' . $user->api_token;
        }
        return $this->getJson($uri, $headers);
    }

    protected function postJsonAuthenticated($uri, $data = [], $user = null, $headers = [])
    {
        if ($user) {
            $headers['Authorization'] = 'Bearer ' . $user->api_token;
        }
        return $this->postJson($uri, $data, $headers);
    }

    protected function putJsonAuthenticated($uri, $data = [], $user = null, $headers = [])
    {
        if ($user) {
            $headers['Authorization'] = 'Bearer ' . $user->api_token;
        }
        return $this->putJson($uri, $data, $headers);
    }

    protected function deleteJsonAuthenticated($uri, $user = null, $headers = [])
    {
        if ($user) {
            $headers['Authorization'] = 'Bearer ' . $user->api_token;
        }
        return $this->deleteJson($uri, [], $headers);
    }
}