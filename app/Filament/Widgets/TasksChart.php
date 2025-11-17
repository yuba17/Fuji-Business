<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Widgets\ChartWidget;

class TasksChart extends ChartWidget
{
    protected ?string $heading = 'Tareas por Estado';
    
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $tasksByStatus = Task::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $labels = ['Pendiente', 'En Progreso', 'Completada', 'Cancelada'];
        $data = [
            $tasksByStatus['pending'] ?? 0,
            $tasksByStatus['in_progress'] ?? 0,
            $tasksByStatus['completed'] ?? 0,
            $tasksByStatus['cancelled'] ?? 0,
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Tareas',
                    'data' => $data,
                    'backgroundColor' => [
                        '#F59E0B', // pending - yellow
                        '#3B82F6', // in_progress - blue
                        '#10B981', // completed - green
                        '#EF4444', // cancelled - red
                    ],
                    'borderColor' => [
                        '#D97706',
                        '#2563EB',
                        '#059669',
                        '#DC2626',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}

