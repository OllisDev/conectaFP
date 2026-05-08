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
                'nombre' => 'IES Miguel Catalán',
                'localidad' => 'Zaragoza',
                'provincia' => 'Zaragoza',
                'codigo_centro' => '50008945',
            ],
            [
                'nombre' => 'CIFP Los Enlaces',
                'localidad' => 'Zaragoza',
                'provincia' => 'Zaragoza',
                'codigo_centro' => '50011234',
            ],
            [
                'nombre' => 'IES Virgen del Pilar',
                'localidad' => 'Zaragoza',
                'provincia' => 'Zaragoza',
                'codigo_centro' => '50008956',
            ],
            [
                'nombre' => 'IES Pablo Serrano',
                'localidad' => 'Zaragoza',
                'provincia' => 'Zaragoza',
                'codigo_centro' => '50008967',
            ],
            [
                'nombre' => 'CPIFP Montearagón',
                'localidad' => 'Huesca',
                'provincia' => 'Huesca',
                'codigo_centro' => '22004567',
            ],
        ];

        foreach ($centros as $centro) {
            CentroEducativo::create($centro);
        }
    }
}
