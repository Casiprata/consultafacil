<?php

namespace App\Filament\Medico\Resources\MedicoResource\Pages;

use App\Filament\Medico\Resources\MedicoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMedico extends EditRecord
{
    protected static string $resource = MedicoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
