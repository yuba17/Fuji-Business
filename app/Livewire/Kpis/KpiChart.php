<?php

namespace App\Livewire\Kpis;

use App\Models\Kpi;
use Livewire\Component;

class KpiChart extends Component
{
    public Kpi $kpi;
    public $period = '30'; // días
    public $chartData = [];

    public function mount(Kpi $kpi)
    {
        $this->kpi = $kpi;
        $this->loadChartData();
    }

    public function updatedPeriod()
    {
        $this->loadChartData();
    }

    protected function loadChartData()
    {
        $days = (int) $this->period;
        $startDate = now()->subDays($days);
        
        $history = $this->kpi->history()
            ->where('recorded_at', '>=', $startDate)
            ->orderBy('recorded_at')
            ->get();
        
        $this->chartData = [
            'labels' => $history->pluck('recorded_at')->map(fn($date) => $date->format('d/m'))->toArray(),
            'values' => $history->pluck('value')->toArray(),
            'target' => $this->kpi->target_value,
        ];
    }

    public function render()
    {
        return view('livewire.kpis.kpi-chart');
    }
}
