<?php

namespace App\Livewire\Dashboards;

use App\Models\Plan;
use App\Models\Kpi;
use App\Models\Task;
use App\Models\Risk;
use Livewire\Component;

class VisualizationDashboard extends Component
{
    public $totalPlans;
    public $totalKpis;
    public $totalTasks;
    public $totalRisks;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->totalPlans = Plan::count();
        $this->totalKpis = Kpi::where('is_active', true)->count();
        $this->totalTasks = Task::count();
        $this->totalRisks = Risk::count();
    }

    public function refresh()
    {
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.dashboards.visualization-dashboard');
    }
}
