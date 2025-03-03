<?php

namespace App\Filament\Paciente\Resources\ConsultaResource\Pages;

use App\Filament\Paciente\Resources\ConsultaResource;
use Auth;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateConsulta extends CreateRecord
{
    protected static string $resource = ConsultaResource::class;


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
