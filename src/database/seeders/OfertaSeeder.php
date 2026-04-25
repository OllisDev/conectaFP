<?php
namespace Database\Seeders;
// database/seeders/OfertaSeeder.php
use Illuminate\Database\Seeder;
use App\Models\Oferta;

class OfertaSeeder extends Seeder
{
    public function run()
    {
        Oferta::factory()->count(20)->create();
    }
}
