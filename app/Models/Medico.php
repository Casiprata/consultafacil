<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medico extends Model
{
    protected $fillable = [
        'user_id',
        'especialidade_id',
        'nome',
        'data_nascimento',
        'nacionalidade',
        'numero_ordem',
        'telefone',
        'bi',
        'copia_bi',
    ];

    protected $attributes = [
        'especialidade_id' => null, // Valor padrão
    ];

    public function especialidade()
    {
        return $this->belongsTo(Especialidade::class);
    }

    public function horarioTrabalho()
    {
        return $this->hasMany(HorarioTrabalho::class);
    }
}
