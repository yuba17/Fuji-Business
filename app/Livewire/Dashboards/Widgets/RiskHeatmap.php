<?php

namespace App\Livewire\Dashboards\Widgets;

use App\Models\Risk;
use App\Services\RiskCalculationService;
use Livewire\Component;

class RiskHeatmap extends Component
{
    public $riskDistribution = [];
    public $totalRisks = 0;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $service = new RiskCalculationService();
        $this->riskDistribution = $service->getRiskDistribution();
        $this->totalRisks = $this->riskDistribution['total'] ?? 0;
    }

    public function render()
    {
        return view('livewire.dashboards.widgets.risk-heatmap');
    }
}
