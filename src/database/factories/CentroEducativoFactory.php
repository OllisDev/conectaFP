<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class CentroEducativoFactory extends Factory
{
    public function definition()
    {
        $faker = \Faker\Factory::create('es_ES');
        return [
            // nombre: string(50)
            'nombre' => 'IES ' . $faker->lastName(),
            // localidad: string(100)
            'localidad' => $faker->city(),
            // provincia: string(100)
            'provincia' => $faker->state(),
            // codigo_centro: string(20), único
            'codigo_centro' => $faker->unique()->bothify('####??'),
        ];
    }
}