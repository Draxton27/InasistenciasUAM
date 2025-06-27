<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reprogramaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('justificacion_id')->constrained('justificaciones')->onDelete('cascade');
            $table->dateTime('fecha_reprogramada');
            $table->string('aula')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reprogramaciones');
    }
};
