<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'data_nascimento',
        'sexo',
        'bi',
        'telefone',
        'endereco',
        'profissao',
        'estado_civil',
        'data_cadastro',
    ];

    // Relacionamentos

    public function diagnosticos()
    {
        return $this->hasMany(Diagnostico::class);
    }

    public function tratamentos()
    {
        return $this->hasMany(Tratamento::class);
    }

    public function consultas()
    {
        return $this->hasMany(Consulta::class);
    }

    public function exames()
    {
        return $this->hasMany(Exame::class);
    }

    public function encerramentos()
    {
        return $this->hasMany(Encerramento::class);
    }
}
