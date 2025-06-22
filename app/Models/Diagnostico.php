<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnostico extends Model
{
    use HasFactory;

    protected $fillable = [
        'paciente_id',
        'data_diagnostico',
        'tipo_tb',
        'sensibilidade',
        'hiv_status',
        'comorbidades',
        'observacoes',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }
}
