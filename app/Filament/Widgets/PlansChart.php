<?php

namespace App\Filament\Widgets;

use App\Models\Plan;
use Filament\Widgets\ChartWidget;

class PlansChart extends ChartWidget
{
    protected ?string $heading = 'Planes por Estado';
    
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $plansByStatus = Plan::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $labels = ['Borrador', 'En Revisión', 'En Progreso', 'Aprobado', 'Archivado'];
        $data = [
            $plansByStatus['draft'] ?? 0,
            $plansByStatus['in_review'] ?? 0,
            $plansByStatus['in_progress'] ?? 0,
            $plansByStatus['approved'] ?? 0,
            $plansByStatus['archived'] ?? 0,
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Planes',
                    'data' => $data,
                    'backgroundColor' => [
                        '#9CA3AF', // draft - gray
                        '#F59E0B', // in_review - yellow
                        '#3B82F6', // in_progress - blue
                        '#10B981', // approved - green
                        '#6B7280', // archived - gray
                    ],
                    'borderColor' => [
                        '#6B7280',
                        '#D97706',
                        '#2563EB',
                        '#059669',
                        '#4B5563',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
