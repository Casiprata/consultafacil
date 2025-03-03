<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    protected $table = "consultas";
    protected $fillable = [
        'paciente_id',
        'especialidade_id',
        'medico_id',
        'data',
        'estado',
        'observacoes',
    ];
}
