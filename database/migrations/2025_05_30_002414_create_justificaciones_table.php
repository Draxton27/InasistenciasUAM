<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('justificaciones', function (Blueprint $table) {
         $table->id();
        $table->foreignId('profesor_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('clase_afectada');
        $table->date('fecha');
        $table->enum('tipo_constancia', ['trabajo', 'enfermedad', 'otro']);
        $table->text('notas_adicionales')->nullable();
        $table->string('archivo')->nullable();
        $table->enum('estado', ['pendiente', 'aceptada', 'rechazada'])->default('pendiente');
        $table->timestamps();
    });

    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('justificaciones');
    }
};
