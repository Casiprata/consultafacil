<?php

namespace App\Filament\Resources\HorarioTrabalhoResource\Pages;

use App\Filament\Resources\HorarioTrabalhoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHorarioTrabalhos extends ListRecords
{
    protected static string $resource = HorarioTrabalhoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
