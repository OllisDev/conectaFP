<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('alumno', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->constrained('usuario')->onDelete('cascade');
            $table->foreignId('id_centro')->constrained('centro_educativo');
            $table->foreignId('id_grado')->constrained('grado');
            $table->date('fecha_nacimiento');
            $table->string('curso', 20);
            $table->string('dni', 20);
            $table->string('cv', 255);
            $table->boolean('disponibilidad')->default(1);
            $table->boolean('eliminado')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumno');
    }
};
