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
        Schema::create('oferta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_empresa')->constrained('empresa')->onDelete('cascade')->nullable(false);
            $table->string('titulo')->nullable(false);
            $table->text('descripcion');
            $table->text('requisitos');
            $table->enum('modalidad', ['Presencial', 'Remoto', 'Híbrido']);
            $table->date('fecha_publicacion');
            $table->enum('estado', ['Abierta', 'Cerrada', 'Pausada'])->default('Abierta');
            $table->boolean('eliminado')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oferta');
    }
};
