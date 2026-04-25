<?php
namespace Database\Seeders;
// database/seeders/SectorSeeder.php
use Illuminate\Database\Seeder;
use App\Models\Sector;

class SectorSeeder extends Seeder
{
    public function run()
    {
        Sector::factory()->count(5)->create();
    }
}
