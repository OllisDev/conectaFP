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
                'direccion' => 'Calle Catedrático José Beltrán Martínez, 2, Zaragoza',
                'telefono' => '976123456',
                'email' => 'info@iesmiguelcatalan.es',
            ],
            [
                'nombre' => 'CIFP Los Enlaces',
                'direccion' => 'Avda. María Zambrano, 4, Zaragoza',
                'telefono' => '976234567',
                'email' => 'contacto@losenlaces.es',
            ],
        ];

        foreach ($centros as $centro) {
            CentroEducativo::create($centro);
        }
    }
}
