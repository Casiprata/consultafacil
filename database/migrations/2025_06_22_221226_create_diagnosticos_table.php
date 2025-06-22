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
        Schema::create('diagnosticos', function (Blueprint $table) {
            $table->id();
             $table->foreignId('paciente_id')->constrained()->onDelete('cascade');
            $table->date('data_diagnostico');
            $table->enum('tipo_tb', ['Pulmonar', 'Extrapulmonar']);
            $table->enum('sensibilidade', ['Sensível', 'Resistente']);
            $table->enum('hiv_status', ['Positivo', 'Negativo', 'Desconhecido']);
            $table->string('comorbidades')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnosticos');
    }
};
