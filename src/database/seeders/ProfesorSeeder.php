<?php
namespace Database\Seeders;
// database/seeders/ProfesorSeeder.php
use Illuminate\Database\Seeder;
use App\Models\Profesor;

class ProfesorSeeder extends Seeder
{
    public function run()
    {
        Profesor::factory()->count(10)->create();
    }
}
