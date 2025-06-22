<?php

namespace App\Filament\Resources\EncerramentoResource\Pages;

use App\Filament\Resources\EncerramentoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEncerramentos extends ListRecords
{
    protected static string $resource = EncerramentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
