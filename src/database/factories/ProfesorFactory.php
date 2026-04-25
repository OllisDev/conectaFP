<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfesorFactory extends Factory
{
    public function definition()
    {
        $faker = \Faker\Factory::create('es_ES');
        return [
            'id_usuario' => \App\Models\Usuario::inRandomOrder()->first()?->id ?? \App\Models\Usuario::factory(),
            'id_centro' => \App\Models\CentroEducativo::inRandomOrder()->first()?->id ?? \App\Models\CentroEducativo::factory(),
            'id_grado' => \App\Models\Grado::inRandomOrder()->first()?->id ?? \App\Models\Grado::factory(),
            'id_departamento' => \App\Models\Departamento::inRandomOrder()->first()?->id ?? \App\Models\Departamento::factory(),
            'dni' => $faker->unique()->regexify('[0-9]{8}[A-Z]'),
            'eliminado' => 0,
        ];
    }
}