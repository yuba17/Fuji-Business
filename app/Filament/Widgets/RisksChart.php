<?php

namespace App\Filament\Widgets;

use App\Models\Risk;
use Filament\Widgets\ChartWidget;

class RisksChart extends ChartWidget
{
    protected ?string $heading = 'Distribución de Riesgos';
    
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $risksByCategory = Risk::selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        $labels = ['Crítico', 'Alto', 'Medio', 'Bajo'];
        $data = [
            $risksByCategory['critico'] ?? 0,
            $risksByCategory['alto'] ?? 0,
            $risksByCategory['medio'] ?? 0,
            $risksByCategory['bajo'] ?? 0,
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Riesgos',
                    'data' => $data,
                    'backgroundColor' => [
                        '#EF4444', // critico - red
                        '#F97316', // alto - orange
                        '#F59E0B', // medio - yellow
                        '#10B981', // bajo - green
                    ],
                    'borderColor' => [
                        '#DC2626',
                        '#EA580C',
                        '#D97706',
                        '#059669',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}

