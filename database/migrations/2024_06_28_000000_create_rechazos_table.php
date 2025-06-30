<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rechazos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('justificacion_id')->constrained('justificaciones')->onDelete('cascade');
            $table->string('comentario');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rechazos');
    }
}; 