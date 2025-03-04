<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HorarioTrabalho extends Model
{
    protected $table = 'horario_trabalhos';
    protected $fillable = [
        'medico_id', 'dia', 'hora_inicio', 'hora_termino'
    ];

    public function medicos()
    {
        return $this->belongsTo(Medico::class, 'medico_id');
    }

}
