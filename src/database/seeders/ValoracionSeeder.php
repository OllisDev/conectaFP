<?php
namespace Database\Seeders;
// database/seeders/ValoracionSeeder.php
use Illuminate\Database\Seeder;
use App\Models\Valoracion;

class ValoracionSeeder extends Seeder
{
    public function run()
    {
        Valoracion::factory()->count(10)->create();
    }
}
