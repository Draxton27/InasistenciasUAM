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
        Schema::table('justificaciones', function (Blueprint $table) {
            $table->dropForeign('justificaciones_profesor_id_foreign');

            $table->dropColumn(['clase_afectada', 'profesor_id']);

            $table->foreignId('clase_profesor_id')->constrained('clase_profesor')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('justificaciones', function (Blueprint $table) {
            $table->dropForeign(['clase_profesor_id']);
            $table->dropColumn('clase_profesor_id');

            $table->string('clase_afectada');
            $table->foreignId('profesor_id')->constrained('users')->onDelete('cascade');
        });
    }

};