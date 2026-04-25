<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlumnoFactory extends Factory
{
    public function definition()
    {
        $faker = \Faker\Factory::create('es_ES');
        return [
            'id_usuario' => \App\Models\Usuario::inRandomOrder()->first()?->id ?? \App\Models\Usuario::factory(),
            'id_centro' => \App\Models\CentroEducativo::inRandomOrder()->first()?->id ?? \App\Models\CentroEducativo::factory(),
            'id_grado' => \App\Models\Grado::inRandomOrder()->first()?->id ?? \App\Models\Grado::factory(),
            'fecha_nacimiento' => $faker->date('Y-m-d', '-18 years'),
            'curso' => $faker->randomElement(['1º', '2º']),
            'dni' => $faker->unique()->regexify('[0-9]{8}[A-Z]'),
            'cv' => $faker->url,
            'disponibilidad' => $faker->boolean(80),
            'eliminado' => 0,
        ];
    }
}