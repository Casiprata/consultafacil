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

    public function especialidade()
    {
        return $this->belongsTo(Especialidade::class, 'especialidade_id');
    }

    public function horarioTrabalho()
    {
        return $this->hasMany(HorarioTrabalho::class);
    }
}
