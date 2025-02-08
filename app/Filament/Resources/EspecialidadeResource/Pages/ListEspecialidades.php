<?php

namespace App\Filament\Resources\EspecialidadeResource\Pages;

use App\Filament\Resources\EspecialidadeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEspecialidades extends ListRecords
{
    protected static string $resource = EspecialidadeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
