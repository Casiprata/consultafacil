<?php

namespace App\Filament\Resources\ExameResource\Pages;

use App\Filament\Resources\ExameResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExame extends EditRecord
{
    protected static string $resource = ExameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
