<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartamentoFactory extends Factory
{
    public function definition()
    {
        $faker = \Faker\Factory::create('es_ES');
        return [
            'nombre' => $faker->randomElement([
                'Informática',
                'Lengua',
                'Matemáticas',
                'Ciencias',
                'Idiomas',
                'Tecnología',
                'Administración',
            ]),
        ];
    }
}