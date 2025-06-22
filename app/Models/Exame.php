<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exame extends Model
{
    use HasFactory;

    protected $fillable = [
        'paciente_id',
        'categoria_exame',
        'tipo_exame',
        'resultado',
        'unidade',
        'valores_referencia',
        'data_exame',
        'observacoes',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }
}
