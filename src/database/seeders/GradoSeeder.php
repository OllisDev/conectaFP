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
            ['nombre' => 'Desarrollo de Aplicaciones Web', 'nivel' => 'Superior'],
            ['nombre' => 'Desarrollo de Aplicaciones Multiplataforma', 'nivel' => 'Superior'],
            ['nombre' => 'Administración de Sistemas Informáticos en Red', 'nivel' => 'Superior'],
            ['nombre' => 'Sistemas Microinformáticos y Redes', 'nivel' => 'Medio'],
            ['nombre' => 'Administración y Finanzas', 'nivel' => 'Superior'],
            ['nombre' => 'Gestión Administrativa', 'nivel' => 'Medio'],
        ];

        foreach ($grados as $grado) {
            Grado::create($grado);
        }
    }
}
