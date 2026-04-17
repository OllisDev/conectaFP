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
        Schema::create('solicitud', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_oferta')->constrained('oferta')->onDelete('cascade');
            $table->foreignId('id_alumno')->constrained('alumno')->onDelete('cascade');
            $table->unique(['id_oferta', 'id_alumno']);
            $table->timestamp('fecha_solicitud')->useCurrent();
            $table->enum('estado', ['Pendiente', 'Revision', 'Aceptada', 'Rechazada'])->default('Pendiente');
            $table->boolean('eliminado')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud');
    }
};
