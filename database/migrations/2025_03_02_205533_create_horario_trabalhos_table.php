<?php

use App\Models\Medico;
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
        Schema::create('horario_trabalhos', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Medico::class)->constrained()->onDelete('cascade');
            $table->date('dia');
            $table->time('hora_inicio');
            $table->time('hora_termino');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horario_trabalhos');
    }
};
