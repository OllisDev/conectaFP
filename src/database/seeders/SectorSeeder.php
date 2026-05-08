<?php
namespace Database\Seeders;
// database/seeders/SectorSeeder.php
use Illuminate\Database\Seeder;
use App\Models\Sector;

class SectorSeeder extends Seeder
{
    public function run()
    {
        $sectores = [
            ['nombre' => 'Tecnología e Informática'],
            ['nombre' => 'Administración y Finanzas'],
            ['nombre' => 'Comercio y Marketing'],
            ['nombre' => 'Sanidad'],
            ['nombre' => 'Hostelería y Turismo'],
            ['nombre' => 'Electricidad y Electrónica'],
            ['nombre' => 'Automoción'],
            ['nombre' => 'Construcción'],
        ];

        foreach ($sectores as $sector) {
            Sector::create($sector);
        }
    }
}
