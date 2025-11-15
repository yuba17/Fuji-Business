<?php

namespace App\Livewire\Dashboards;

use App\Models\Plan;
use App\Models\Kpi;
use App\Models\Task;
use App\Models\Risk;
use Livewire\Component;

class ManagerDashboard extends Component
{
    public $myPlans;
    public $activePlans;
    public $myKpis;
    public $myRisks;
    public $myTasks;
    public $recentPlans;
    public $recentTasks;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $user = auth()->user();
        
        $this->myPlans = Plan::where('manager_id', $user->id)->count();
        $this->activePlans = Plan::where('manager_id', $user->id)
            ->where('status', 'in_progress')
            ->count();
        $this->myKpis = Kpi::whereHas('plan', function($q) use ($user) {
            $q->where('manager_id', $user->id);
        })->where('is_active', true)->count();
        $this->myRisks = Risk::whereHas('plan', function($q) use ($user) {
            $q->where('manager_id', $user->id);
        })->count();
        $this->myTasks = Task::whereHas('plan', function($q) use ($user) {
            $q->where('manager_id', $user->id);
        })->where('status', '!=', 'done')->count();
        $this->recentPlans = Plan::where('manager_id', $user->id)
            ->with(['planType', 'area'])
            ->latest()
            ->take(5)
            ->get();
        $this->recentTasks = Task::whereHas('plan', function($q) use ($user) {
            $q->where('manager_id', $user->id);
        })->with(['plan', 'assignedUser'])
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
        return view('livewire.dashboards.manager-dashboard');
    }
}
