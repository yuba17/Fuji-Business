<?php

namespace App\Livewire\Dashboards\Widgets;

use App\Models\Decision;
use Livewire\Component;

class RecentDecisions extends Component
{
    public $recentDecisions = [];
    public $pendingDecisions = 0;

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Decisiones recientes (últimas 5)
        $this->recentDecisions = Decision::with(['proponent', 'plans'])
            ->latest('decision_date')
            ->take(5)
            ->get();

        // Decisiones pendientes de aprobación
        $this->pendingDecisions = Decision::whereIn('status', ['proposed', 'discussion', 'pending_approval'])
            ->count();
    }

    public function render()
    {
        return view('livewire.dashboards.widgets.recent-decisions');
    }
}
