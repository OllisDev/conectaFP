<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class AsignacionFactory extends Factory
{
    public function definition()
    {
        $faker = \Faker\Factory::create('es_ES');
        return [
            'id_alumno' => \App\Models\Alumno::inRandomOrder()->first()?->id ?? \App\Models\Alumno::factory(),
            'id_profesor' => \App\Models\Profesor::inRandomOrder()->first()?->id ?? \App\Models\Profesor::factory(),
            'id_empresa' => \App\Models\Empresa::inRandomOrder()->first()?->id ?? \App\Models\Empresa::factory(),
            'estado' => $faker->randomElement(['Activa', 'Finalizada']),
            'fecha_asignacion' => $faker->dateTimeBetween('-1 years', 'now'),
        ];
    }
}