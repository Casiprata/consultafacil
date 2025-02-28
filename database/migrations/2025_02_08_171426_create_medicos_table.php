<?php

use App\Models\Especialidade;
use App\Models\User;
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
        Schema::create('medicos', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Especialidade::class)->nullable()->constrained()->nullOnDelete();
            $table->string('nome')->nullable();
            $table->date('data_nascimento')->nullable();
            $table->string('nacionalidade')->nullable();
            $table->integer('numero_ordem')->unique()->nullable();
            $table->string('telefone')->unique()->nullable();
            $table->string('bi')->unique()->nullable();
            $table->string('copia_bi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicos');
    }
};

