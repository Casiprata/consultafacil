<?php

use App\Models\Especialidade;
use App\Models\Medico;
use App\Models\Paciente;
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
        Schema::create('consultas', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Paciente::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Especialidade::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Medico::class)->constrained()->onDelete('cascade');
            $table->dateTime('data')->nullable();
            $table->enum('estado', ['agendada', 'Cancelada', 'Concluida'])->default('agendada');
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultas');
    }
};
