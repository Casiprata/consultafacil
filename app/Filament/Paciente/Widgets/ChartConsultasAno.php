<?php

namespace App\Filament\Paciente\Widgets;

use App\Models\Consulta;
use App\Models\HorarioTrabalho;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class ChartConsultasAno extends ChartWidget
{
    protected static ?string $heading = '📊 Consultas por Ano';

    protected function getData(): array
    {
        $usuarioId = Auth::id();

        $consultasPorAno = Consulta::whereHas('paciente', function ($query) use ($usuarioId) {
                $query->where('user_id', $usuarioId);
            })
            ->join('horario_trabalhos', 'consultas.horario_trabalho_id', '=', 'horario_trabalhos.id')
            ->selectRaw('YEAR(horario_trabalhos.dia) as ano, COUNT(*) as total')
            ->groupBy('ano')
            ->pluck('total', 'ano')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Consultas',
                    'data' => array_values($consultasPorAno),
                    'backgroundColor' => [
                        '#4CAF50', '#2196F3', '#FF9800', '#E91E63', '#9C27B0'
                    ],
                    'borderColor' => '#ffffff',
                    'borderWidth' => 2,
                    'borderRadius' => 8,
                    'hoverBackgroundColor' => '#FFC107',
                    'hoverBorderColor' => '#000',
                ],
            ],
            'labels' => array_keys($consultasPorAno),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                    'labels' => [
                        'color' => '#333',
                        'font' => [
                            'size' => 14,
                            'weight' => 'bold',
                        ],
                    ],
                ],
                'tooltip' => [
                    'enabled' => true,
                    'backgroundColor' => '#222',
                    'titleFont' => [
                        'size' => 14,
                        'weight' => 'bold',
                    ],
                    'bodyFont' => [
                        'size' => 12,
                    ],
                ],
            ],
            'scales' => [
                'x' => [
                    'grid' => ['display' => false],
                    'ticks' => ['color' => '#444', 'font' => ['size' => 12]],
                ],
                'y' => [
                    'beginAtZero' => true,
                    'grid' => ['color' => 'rgba(200, 200, 200, 0.3)'],
                    'ticks' => ['color' => '#444', 'font' => ['size' => 12]],
                ],
            ],
        ];
    }
}
