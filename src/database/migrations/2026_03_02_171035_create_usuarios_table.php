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
        Schema::create('usuario', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->nullable(false);
            $table->string('apellidos', 100)->nullable(false);
            $table->string('contrasena', 255)->nullable(false);
            $table->string('email', 100)->nullable(false)->unique();
            $table->string('telefono', 20)->nullable(false);
            $table->boolean('activo')->default(1);
            $table->timestamp('fecha_registro')->useCurrent();
            $table->string('api_token', 80)->unique()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario');

    }
};
