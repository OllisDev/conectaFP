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
        Schema::create('centro_educativo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50);
            $table->string('localidad', 100);
            $table->string('provincia', 100);
            $table->string('codigo_centro', 20);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centro_educativo');
    }
};
