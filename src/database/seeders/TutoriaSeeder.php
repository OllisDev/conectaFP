<?php
namespace Database\Seeders;
// database/seeders/TutoriaSeeder.php
use Illuminate\Database\Seeder;
use App\Models\Tutoria;

class TutoriaSeeder extends Seeder
{
    public function run()
    {
        Tutoria::factory()->count(10)->create();
    }
}
