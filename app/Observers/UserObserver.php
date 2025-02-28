<?php

namespace App\Observers;

use App\Models\Medico;
use App\Models\Paciente;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // Se o usuário for PACIENTE, cria um registro na tabela pacientes
        if ($user->role === 'PACIENTE') {
            Paciente::create([
                'user_id' => $user->id,
                'nome' => $user->name,
            ]);
        }

        // Se o usuário for MÉDICO, cria um registro na tabela médicos
        if ($user->role === 'MEDICO') {
            Medico::create([
                'user_id' => $user->id,
                'nome' => $user->name,
                'especialidade_id' => null, // Definir como nulo
            ]);
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
