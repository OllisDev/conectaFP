<?php
namespace Database\Seeders;
// database/seeders/SolicitudSeeder.php
use Illuminate\Database\Seeder;
use App\Models\Solicitud;

class SolicitudSeeder extends Seeder
{
    public function run()
    {
        Solicitud::factory()->count(20)->create();
    }
}
