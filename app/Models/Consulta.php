<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    protected $table = "consultas";
    protected $fillable = [
        'paciente_id',
        'especialidade_id',
        'horario_trabalho_id',
        'estado',
        'diagnostico',
        'prescricao',
        'observacoes',
    ];

    protected $casts = [
        'prescricao' => 'array',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function especialidade()
    {
        return $this->belongsTo(Especialidade::class);
    }

    public function horarioTrabalho()
    {
        return $this->belongsTo(HorarioTrabalho::class);
    }

   public function medicos()
    {
        return $this->belongsTo(Medico::class);
    }


}
