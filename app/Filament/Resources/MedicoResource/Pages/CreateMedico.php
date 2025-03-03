<?php

namespace App\Filament\Resources\MedicoResource\Pages;

use App\Filament\Resources\MedicoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMedico extends CreateRecord
{
    protected static string $resource = MedicoResource::class;


    protected static string $icon = "heroicon-o-user";

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
