<?php

namespace App\Filament\Paciente\Resources\PacienteResource\Pages;

use App\Filament\Paciente\Resources\PacienteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePaciente extends CreateRecord
{
    protected static string $resource = PacienteResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
