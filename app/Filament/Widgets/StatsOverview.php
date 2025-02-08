<?php

namespace App\Filament\Widgets;

use App\Models\Medico;
use App\Models\Paciente;
use App\Models\ServiceRequest;
use App\Models\Servico;
use App\Models\User;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Usuários', User::query()->count())
            ->description('Usuários do sistema')
            ->descriptionIcon('heroicon-o-users', IconPosition::Before)
            ->chart([7, 2, 18, 3, 15, 4, 17])
            ->color('warning'),
            Stat::make('Médicos', Medico::query()->count())
            ->description('Medicos cadastrados')
            ->descriptionIcon('heroicon-s-user', IconPosition::Before)
            ->chart([0,0,2,7,3,10])
            ->color('success'),
            Stat::make('Pacientes', Paciente::query()->count())
            ->description('Pacientes cadastrados')
            ->descriptionIcon('heroicon-s-user-group', IconPosition::Before)
            ->chart([20,0,2,7,3,10])
            ->color('danger'),
        ];
    }
}
