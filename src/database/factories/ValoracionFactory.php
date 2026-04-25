<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class ValoracionFactory extends Factory
{
    public function definition()
    {
        $faker = \Faker\Factory::create('es_ES');
        return [
            'id_tutoria' => \App\Models\Tutoria::inRandomOrder()->first()?->id ?? \App\Models\Tutoria::factory(),
            'comentario' => $faker->optional()->sentence(),
            'fecha' => $faker->dateTimeBetween('-1 years', 'now'),
        ];
    }
}