<?php

namespace App\Filament\Paciente\Widgets;

use App\Models\Consulta;
use App\Models\Especialidade;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class ChartTiposConsultas extends ChartWidget
{
    protected static ?string $heading = '🩺 Consultas por Especialidade';

    protected function getData(): array
    {
        $usuarioId = Auth::id();

        $consultasPorEspecialidade = Consulta::whereHas('paciente', function ($query) use ($usuarioId) {
                $query->where('user_id', $usuarioId);
            })
            ->join('especialidades', 'consultas.especialidade_id', '=', 'especialidades.id')
            ->selectRaw('especialidades.nome as especialidade, COUNT(*) as total')
            ->groupBy('especialidade')
            ->pluck('total', 'especialidade')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Consultas',
                    'data' => array_values($consultasPorEspecialidade),
                    'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4CAF50', '#9C27B0'],
                    'hoverOffset' => 10,
                ],
            ],
            'labels' => array_keys($consultasPorEspecialidade),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
