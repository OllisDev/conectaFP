<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class SectorFactory extends Factory
{
    public function definition()
    {
        $faker = \Faker\Factory::create('es_ES');
        return [
            'nombre' => $faker->randomElement([
                'Tecnologías de la Información',
                'Educación',
                'Sanidad',
                'Construcción',
                'Hostelería',
                'Comercio',
                'Transporte',
                'Administración Pública',
            ]),
        ];
    }
}