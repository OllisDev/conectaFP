<?php
namespace Database\Seeders;
// database/seeders/UsuarioSeeder.php
use Illuminate\Database\Seeder;
use App\Models\Usuario;

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        Usuario::factory()->count(20)->create();
    }
}