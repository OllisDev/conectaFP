<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Orden lógico para respetar relaciones
        \App\Models\Sector::factory(8)->create();
        \App\Models\Grado::factory(6)->create();
        \App\Models\CentroEducativo::factory(5)->create();
        \App\Models\Departamento::factory(7)->create();

        // Usuarios para alumnos
        \App\Models\Usuario::factory(100)->create();
        \App\Models\Alumno::factory(100)->create();

        // Usuarios para empresas y profesores
        \App\Models\Usuario::factory(20)->create(); // empresas
        \App\Models\Usuario::factory(20)->create(); // profesores

        \App\Models\Empresa::factory(20)->create();
        \App\Models\Profesor::factory(20)->create();
        \App\Models\Oferta::factory(30)->create();

        // Si quieres poblar el resto:
        // \App\Models\Solicitud::factory(30)->create();
        // \App\Models\Asignacion::factory(30)->create();
        // \App\Models\Tutoria::factory(30)->create();
        // \App\Models\Valoracion::factory(30)->create();
    }
}