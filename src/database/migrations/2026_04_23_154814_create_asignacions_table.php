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
        Schema::create('asignacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_alumno')->constrained('alumno')->onDelete('cascade');
            $table->foreignId('id_profesor')->constrained('profesor')->onDelete('cascade');
            $table->foreignId('id_empresa')->constrained('empresa')->onDelete('cascade');
            $table->enum('estado', ['Activa', 'Finalizada']);
            $table->timestamp('fecha_asignacion')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignacion');
    }
};
