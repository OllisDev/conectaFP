<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class TutoriaFactory extends Factory
{
    public function definition()
    {
        $faker = \Faker\Factory::create('es_ES');
        $fechaInicio = $faker->dateTimeBetween('-1 years', 'now');
        $estado = $faker->randomElement(['Activa', 'Finalizada', 'Cancelada']);
        $fechaFin = $estado === 'Activa' ? null : $faker->dateTimeBetween($fechaInicio, 'now');
        return [
            'id_alumno' => \App\Models\Alumno::inRandomOrder()->first()?->id ?? \App\Models\Alumno::factory(),
            'id_profesor' => $faker->boolean(80) ? (\App\Models\Profesor::inRandomOrder()->first()?->id ?? \App\Models\Profesor::factory()) : null,
            'id_empresa' => \App\Models\Empresa::inRandomOrder()->first()?->id ?? \App\Models\Empresa::factory(),
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'estado' => $estado,
            'eliminado' => 0,
        ];
    }
}