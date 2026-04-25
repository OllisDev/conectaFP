<?php
namespace Database\Seeders;
// database/seeders/CentroEducativoSeeder.php
use Illuminate\Database\Seeder;
use App\Models\CentroEducativo;

class CentroEducativoSeeder extends Seeder
{
    public function run()
    {
        CentroEducativo::factory()->count(5)->create();
    }
}
