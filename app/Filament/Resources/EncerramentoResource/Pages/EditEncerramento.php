<?php

namespace App\Filament\Resources\EncerramentoResource\Pages;

use App\Filament\Resources\EncerramentoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEncerramento extends EditRecord
{
    protected static string $resource = EncerramentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
