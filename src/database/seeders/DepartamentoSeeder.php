<?php
namespace Database\Seeders;
// database/seeders/DepartamentoSeeder.php
use Illuminate\Database\Seeder;
use App\Models\Departamento;

class DepartamentoSeeder extends Seeder
{
    public function run()
    {
        Departamento::factory()->count(5)->create();
    }
}
