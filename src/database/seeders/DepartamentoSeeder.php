<?php
namespace Database\Seeders;
// database/seeders/DepartamentoSeeder.php
use Illuminate\Database\Seeder;
use App\Models\Departamento;

class DepartamentoSeeder extends Seeder
{
    public function run()
    {
        $departamentos = [
            ['nombre' => 'Recursos Humanos'],
            ['nombre' => 'Desarrollo de Software'],
            ['nombre' => 'Sistemas y Redes'],
            ['nombre' => 'Marketing Digital'],
            ['nombre' => 'Administración'],
            ['nombre' => 'Atención al Cliente'],
            ['nombre' => 'Producción'],
        ];

        foreach ($departamentos as $departamento) {
            Departamento::create($departamento);
        }
    }
}
