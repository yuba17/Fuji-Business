<?php

namespace App\Livewire\Dashboards;

use App\Models\Plan;
use App\Models\Kpi;
use App\Models\Task;
use App\Models\Risk;
use Livewire\Component;

class DirectorDashboard extends Component
{
    public $totalPlans;
    public $activePlans;
    public $totalKpis;
    public $criticalRisks;
    public $pendingTasks;
    public $recentPlans;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->totalPlans = Plan::count();
        $this->activePlans = Plan::where('status', 'in_progress')->count();
        $this->totalKpis = Kpi::where('is_active', true)->count();
        $this->criticalRisks = Risk::where('category', 'critico')->count();
        $this->pendingTasks = Task::where('status', '!=', 'done')->count();
        $this->recentPlans = Plan::with(['planType', 'area', 'manager'])
            ->latest()
            ->take(5)
            ->get();
    }

    public function refresh()
    {
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.dashboards.director-dashboard');
    }
}
