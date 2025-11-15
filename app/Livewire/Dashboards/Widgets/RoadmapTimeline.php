<?php

namespace App\Livewire\Dashboards\Widgets;

use App\Models\Milestone;
use Livewire\Component;

class RoadmapTimeline extends Component
{
    public $upcomingMilestones = [];
    public $delayedMilestones = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Próximos milestones (próximos 30 días)
        $this->upcomingMilestones = Milestone::where('status', '!=', 'completed')
            ->where('status', '!=', 'cancelled')
            ->where('target_date', '>=', now())
            ->where('target_date', '<=', now()->addDays(30))
            ->with(['plan', 'responsible'])
            ->orderBy('target_date', 'asc')
            ->take(5)
            ->get();

        // Milestones retrasados
        $this->delayedMilestones = Milestone::where('status', '!=', 'completed')
            ->where('status', '!=', 'cancelled')
            ->where('target_date', '<', now())
            ->with(['plan', 'responsible'])
            ->orderBy('target_date', 'asc')
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.dashboards.widgets.roadmap-timeline');
    }
}
