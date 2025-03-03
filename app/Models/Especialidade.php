<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Especialidade extends Model
{
    protected $fillable = [
        'nome',
        'num_max_consultas',
        'descricao',
    ];


    public function medicos()
    {
        return $this->hasMany(Medico::class);
    }
}
