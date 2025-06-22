<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encerramento extends Model
{
    use HasFactory;

    protected $fillable = [
        'paciente_id',
        'data_encerramento',
        'tipo_desfecho',
        'observacoes',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }
}
