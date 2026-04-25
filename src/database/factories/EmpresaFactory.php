<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmpresaFactory extends Factory
{
    public function definition()
    {
        $faker = \Faker\Factory::create('es_ES');
        return [
            'id_usuario' => \App\Models\Usuario::inRandomOrder()->first()?->id ?? \App\Models\Usuario::factory(),
            'id_sector' => \App\Models\Sector::inRandomOrder()->first()?->id ?? \App\Models\Sector::factory(),
            'nif' => $faker->unique()->regexify('[A-HJ-NP-SUVW][0-9]{7}[0-9A-J]'),
            'descripcion' => $faker->sentence,
            'direccion' => $faker->address,
            'web' => $faker->url,
            'activo' => 1,
        ];
    }
}