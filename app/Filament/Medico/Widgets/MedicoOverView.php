<?php

namespace App\Filament\Medico\Widgets;

use App\Models\Consulta;
use App\Models\Medico;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class MedicoOverView extends BaseWidget
{
    protected function getStats(): array
    {
        $medicoId = Medico::where('user_id', Auth::id())->value('id');

return [
    Stat::make('Consultas', Consulta::whereHas('horarioTrabalho', function ($query) use ($medicoId) {
            $query->where('medico_id', $medicoId);
        })->count())
        ->description('Minhas Consultas')
        ->descriptionIcon('heroicon-o-users', IconPosition::Before)
        ->chart([7, 2, 18, 3, 15, 4, 17])
        ->color('success'),
];
    }
}
