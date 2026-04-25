<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class UsuarioFactory extends Factory
{
    public function definition()
    {
        $faker = \Faker\Factory::create('es_ES');
        return [
            // nombre: string(50), requerido
            'nombre' => $faker->firstName(),
            // apellidos: string(100), nullable
            'apellidos' => $faker->optional()->lastName . ' ' . $faker->optional()->lastName,
            // contrasena: string(255), requerido
            'contrasena' => bcrypt('password'),
            // email: string(100), requerido, único
            'email' => $faker->unique()->safeEmail,
            // telefono: string(20), requerido
            'telefono' => $faker->unique()->numerify('6########'),
            // activo: boolean, default 1
            'activo' => 1,
            // fecha_registro: timestamp, default now
            'fecha_registro' => now(),
            // api_token: string(80), único, nullable
            'api_token' => $faker->boolean(70) ? $faker->unique()->regexify('[A-Za-z0-9]{36}') : null,
        ];
    }
}