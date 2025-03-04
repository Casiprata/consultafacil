<?php

namespace App\Filament\Paciente\Resources\ConsultaResource\Pages;

use App\Filament\Paciente\Resources\ConsultaResource;
use App\Models\Paciente;
use Auth;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateConsulta extends CreateRecord
{
    protected static string $resource = ConsultaResource::class;

    public function mutateFormDataBeforeCreate(array $data): array
{
    $data['paciente_id'] = Paciente::where('user_id', Auth::id())->value('id');
    return $data;
}

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
