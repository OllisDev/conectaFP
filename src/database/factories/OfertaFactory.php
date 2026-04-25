<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfertaFactory extends Factory
{
    public function definition()
    {
        $faker = \Faker\Factory::create('es_ES');
        return [
            'id_empresa' => \App\Models\Empresa::inRandomOrder()->first()?->id ?? \App\Models\Empresa::factory(),
            'titulo' => $faker->sentence(3),
            'descripcion' => $faker->paragraph,
            'requisitos' => implode(', ', $faker->words(3)),
            'modalidad' => $faker->randomElement(['Presencial', 'Remoto', 'Híbrido']),
            'fecha_publicacion' => $faker->dateTimeBetween('-1 years', 'now'),
            'estado' => $faker->randomElement(['Abierta', 'Cerrada', 'Pausada']),
            'eliminado' => 0,
        ];
    }
}