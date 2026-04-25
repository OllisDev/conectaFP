<?php
namespace Database\Seeders;
// database/seeders/AsignacionSeeder.php
use Illuminate\Database\Seeder;
use App\Models\Asignacion;

class AsignacionSeeder extends Seeder
{
    public function run()
    {
        Asignacion::factory()->count(10)->create();
    }
}
