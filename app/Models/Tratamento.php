<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tratamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'paciente_id',
        'esquema_terapeutico',
        'data_inicio',
        'data_fim',
        'observacoes',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }
}
