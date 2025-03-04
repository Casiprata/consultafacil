<?php

namespace App\Filament\Paciente\Widgets;

use App\Models\Consulta;
use App\Models\Paciente;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class PacienteOverView extends BaseWidget
{

    protected function getStats(): array
    {
        $pacienteId = Paciente::where('user_id', Auth::id())->value('id');
        return [

            Stat::make('Consultas', Consulta::where('paciente_id', $pacienteId)->count())
            ->description('Minhas Consultas')
            ->descriptionIcon('heroicon-o-users', IconPosition::Before)
            ->chart([7, 2, 18, 3, 15, 4, 17])
            ->color('success'),
        ];
    }
}
