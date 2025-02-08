<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $fillable = [
       "user_id",
       "nome",
       "data_nascimento",
       "nacionalidade",
       "provincia",
       "municipio",
       "morada",
       "telefone",
    ];
}
