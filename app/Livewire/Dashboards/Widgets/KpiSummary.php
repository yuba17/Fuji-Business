<?php

namespace App\Livewire\Dashboards\Widgets;

use App\Models\Kpi;
use Livewire\Component;

class KpiSummary extends Component
{
    public $totalKpis;
    public $activeKpis;
    public $onTrackKpis;
    public $atRiskKpis;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->totalKpis = Kpi::where('is_active', true)->count();
        $this->activeKpis = Kpi::where('is_active', true)->count();
        
        // KPIs on track (valor actual >= objetivo * 0.8)
        $this->onTrackKpis = Kpi::where('is_active', true)
            ->whereRaw('current_value >= target_value * 0.8')
            ->count();
        
        // KPIs at risk (valor actual < objetivo * 0.8)
        $this->atRiskKpis = Kpi::where('is_active', true)
            ->whereRaw('current_value < target_value * 0.8')
            ->count();
    }

    public function render()
    {
        return view('livewire.dashboards.widgets.kpi-summary');
    }
}
