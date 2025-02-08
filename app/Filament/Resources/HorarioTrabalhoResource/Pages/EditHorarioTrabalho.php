<?php

namespace App\Filament\Resources\HorarioTrabalhoResource\Pages;

use App\Filament\Resources\HorarioTrabalhoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHorarioTrabalho extends EditRecord
{
    protected static string $resource = HorarioTrabalhoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
