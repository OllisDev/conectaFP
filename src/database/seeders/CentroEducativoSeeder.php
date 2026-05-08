<?php
namespace Database\Seeders;
// database/seeders/CentroEducativoSeeder.php
use Illuminate\Database\Seeder;
use App\Models\CentroEducativo;

class CentroEducativoSeeder extends Seeder
{
    public function run()
    {
        $centros = [
            [
                'codigo_centro' => '50008945',
                'nombre' => 'IES Miguel Catalán',
                'localidad' => 'Zaragoza',
                'provincia' => 'Zaragoza',
            ],
            [
                'codigo_centro' => '50011234',
                'nombre' => 'CIFP Los Enlaces',
                'localidad' => 'Zaragoza',
                'provincia' => 'Zaragoza',
            ],
            [
                'codigo_centro' => '50008956',
                'nombre' => 'IES Virgen del Pilar',
                'localidad' => 'Zaragoza',
                'provincia' => 'Zaragoza',
            ],
            [
                'codigo_centro' => '50008967',
                'nombre' => 'IES Pablo Serrano',
                'localidad' => 'Zaragoza',
                'provincia' => 'Zaragoza',
            ],
            [
                'codigo_centro' => '22004567',
                'nombre' => 'CPIFP Montearagón',
                'localidad' => 'Huesca',
                'provincia' => 'Huesca',
            ],
        ];

        foreach ($centros as $centro) {
            CentroEducativo::updateOrCreate(
                ['codigo_centro' => $centro['codigo_centro']],
                $centro
            );
        }
    }
}
