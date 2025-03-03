<?php

namespace App\Filament\Resources\EspecialidadeResource\Pages;

use App\Filament\Resources\EspecialidadeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEspecialidade extends EditRecord
{
    protected static string $resource = EspecialidadeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
