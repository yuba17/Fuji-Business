<?php

namespace App\Livewire\Dashboards\Widgets;

use App\Models\Plan;
use Livewire\Component;

class PlanStatus extends Component
{
    public $totalPlans;
    public $draftPlans;
    public $inProgressPlans;
    public $approvedPlans;
    public $closedPlans;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->totalPlans = Plan::count();
        $this->draftPlans = Plan::where('status', 'draft')->count();
        $this->inProgressPlans = Plan::where('status', 'in_progress')->count();
        $this->approvedPlans = Plan::where('status', 'approved')->count();
        $this->closedPlans = Plan::where('status', 'closed')->count();
    }

    public function render()
    {
        return view('livewire.dashboards.widgets.plan-status');
    }
}
