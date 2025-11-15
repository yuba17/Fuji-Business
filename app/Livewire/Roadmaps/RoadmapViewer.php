<?php

namespace App\Livewire\Roadmaps;

use App\Models\Plan;
use App\Models\Milestone;
use Livewire\Component;

class RoadmapViewer extends Component
{
    public Plan $plan;
    public $milestones;
    public $viewMode = 'gantt'; // 'gantt' o 'list'
    public $selectedMilestone = null;

    public function mount(Plan $plan, $milestones)
    {
        $this->plan = $plan;
        $this->milestones = $milestones;
    }

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
    }

    public function selectMilestone($milestoneId)
    {
        $this->selectedMilestone = $milestoneId;
    }

    public function render()
    {
        return view('livewire.roadmaps.roadmap-viewer');
    }
}
