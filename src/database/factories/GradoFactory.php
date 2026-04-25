<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class GradoFactory extends Factory
{
    public function definition()
    {
        $faker = \Faker\Factory::create('es_ES');
        return [
            'nombre' => $faker->randomElement([
                'Desarrollo de Aplicaciones Web',
                'Administración de Sistemas Informáticos',
                'Educación Infantil',
                'Integración Social',
                'Gestión Administrativa',
                'Mecatrónica Industrial',
            ]),
            'tipo' => $faker->randomElement(['Grado medio', 'Grado superior']),
            'familia_profesional' => $faker->randomElement([
                'Informática y Comunicaciones',
                'Servicios Socioculturales',
                'Administración y Gestión',
                'Instalación y Mantenimiento',
            ]),
            'codigo_grado' => $faker->unique()->bothify('IFC###'),
        ];
    }
}