<?php
namespace Database\Seeders;
// database/seeders/GradoSeeder.php
use Illuminate\Database\Seeder;
use App\Models\Grado;

class GradoSeeder extends Seeder
{
    public function run()
    {
        Grado::factory()->count(5)->create();
    }
}
