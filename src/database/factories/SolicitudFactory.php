<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class SolicitudFactory extends Factory
{
    public function definition()
    {
        $faker = \Faker\Factory::create('es_ES');
        $oferta = \App\Models\Oferta::inRandomOrder()->first();
        $alumno = \App\Models\Alumno::inRandomOrder()->first();
        $empresa = $oferta ? $oferta->id_empresa : (\App\Models\Empresa::inRandomOrder()->first()?->id ?? \App\Models\Empresa::factory());

        return [
            'id_oferta' => $oferta?->id ?? \App\Models\Oferta::factory(),
            'id_alumno' => $alumno?->id ?? \App\Models\Alumno::factory(),
            'id_empresa' => $empresa,
            'fecha_solicitud' => $faker->dateTimeBetween('-1 years', 'now'),
            'estado' => $faker->randomElement(['Pendiente', 'Revision', 'Aceptada', 'Rechazada']),
            'eliminado' => 0,
        ];
    }
}