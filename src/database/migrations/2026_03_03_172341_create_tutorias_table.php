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
        Schema::create('tutoria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_alumno')->nullable(false)->constrained('alumno')->onDelete('restrict');
            $table->foreignId('id_profesor')->nullable()->constrained('profesor')->onDelete('set null');
            $table->foreignId('id_empresa')->nullable(false)->constrained('empresa')->onDelete('restrict');
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->enum('estado', ['Activa', 'Finalizada', 'Cancelada'])->default('Activa');
            $table->boolean('eliminado')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutoria');
    }
};
