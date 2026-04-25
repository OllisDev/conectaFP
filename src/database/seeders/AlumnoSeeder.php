<?php
namespace Database\Seeders;
// database/seeders/AlumnoSeeder.php
use Illuminate\Database\Seeder;
use App\Models\Alumno;

class AlumnoSeeder extends Seeder
{
    public function run()
    {
        Alumno::factory()->count(20)->create();
    }
}
