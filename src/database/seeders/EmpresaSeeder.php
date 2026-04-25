<?php
namespace Database\Seeders;
// database/seeders/EmpresaSeeder.php
use Illuminate\Database\Seeder;
use App\Models\Empresa;

class EmpresaSeeder extends Seeder
{
    public function run()
    {
        Empresa::factory()->count(10)->create();
    }
}
