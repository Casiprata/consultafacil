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
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
             $table->string('nome');
            $table->date('data_nascimento')->nullable();
            $table->enum('sexo', ['Masculino', 'Feminino', 'Outro'])->nullable();
            $table->string('bi')->nullable();
            $table->string('telefone')->nullable();
            $table->string('endereco')->nullable();
            $table->string('profissao')->nullable();
            $table->string('estado_civil')->nullable();
            $table->date('data_cadastro')->default(now());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};
