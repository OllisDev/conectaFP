<?php
namespace Database\Seeders;
// database/seeders/GradoSeeder.php
use Illuminate\Database\Seeder;
use App\Models\Grado;

class GradoSeeder extends Seeder
{
    public function run()
    {
        $grados = [
            [
                'codigo_grado' => 'IFC303',
                'nombre' => 'Desarrollo de Aplicaciones Web',
                'tipo' => 'Grado superior',
                'familia_profesional' => 'Informática y Comunicaciones',
            ],
            [
                'codigo_grado' => 'IFC302',
                'nombre' => 'Desarrollo de Aplicaciones Multiplataforma',
                'tipo' => 'Grado superior',
                'familia_profesional' => 'Informática y Comunicaciones',
            ],
            [
                'codigo_grado' => 'IFC301',
                'nombre' => 'Administración de Sistemas Informáticos en Red',
                'tipo' => 'Grado superior',
                'familia_profesional' => 'Informática y Comunicaciones',
            ],
            [
                'codigo_grado' => 'IFC202',
                'nombre' => 'Sistemas Microinformáticos y Redes',
                'tipo' => 'Grado medio',
                'familia_profesional' => 'Informática y Comunicaciones',
            ],
            [
                'codigo_grado' => 'ADG301',
                'nombre' => 'Administración y Finanzas',
                'tipo' => 'Grado superior',
                'familia_profesional' => 'Administración y Gestión',
            ],
            [
                'codigo_grado' => 'ADG201',
                'nombre' => 'Gestión Administrativa',
                'tipo' => 'Grado medio',
                'familia_profesional' => 'Administración y Gestión',
            ],
        ];

        foreach ($grados as $grado) {
            Grado::updateOrCreate(
                ['codigo_grado' => $grado['codigo_grado']],
                $grado
            );
        }
    }
}
