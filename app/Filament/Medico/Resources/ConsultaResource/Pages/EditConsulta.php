<?php

namespace App\Filament\Medico\Resources\ConsultaResource\Pages;

use App\Filament\Medico\Resources\ConsultaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConsulta extends EditRecord
{
    protected static string $resource = ConsultaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
