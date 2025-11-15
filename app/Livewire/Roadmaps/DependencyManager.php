<?php

namespace App\Livewire\Roadmaps;

use App\Models\Milestone;
use App\Models\MilestoneDependency;
use App\Models\Plan;
use App\Services\DependencyService;
use Livewire\Component;

class DependencyManager extends Component
{
    public Plan $plan;
    public $milestoneId = null;
    public $predecessorId = '';
    public $successorId = '';
    public $type = 'fs';
    public $lagDays = 0;
    public $showAddModal = false;
    public $availableMilestones = [];
    public $dependencies = [];

    public function mount(Plan $plan, $milestoneId = null)
    {
        $this->plan = $plan;
        $this->milestoneId = $milestoneId;
        $this->loadDependencies();
    }

    public function loadDependencies()
    {
        if ($this->milestoneId) {
            $milestone = Milestone::findOrFail($this->milestoneId);
            $this->dependencies = [
                'predecessors' => $milestone->predecessorDependencies()->with('predecessor')->get(),
                'successors' => $milestone->successorDependencies()->with('successor')->get(),
            ];
            $this->availableMilestones = $this->plan->milestones()
                ->where('id', '!=', $this->milestoneId)
                ->orderBy('name')
                ->get();
        } else {
            // Todas las dependencias del plan
            $this->dependencies = MilestoneDependency::whereHas('predecessor', function($q) {
                $q->where('plan_id', $this->plan->id);
            })->with(['predecessor', 'successor'])->get();
            $this->availableMilestones = $this->plan->milestones()->orderBy('name')->get();
        }
    }

    public function openAddModal()
    {
        $this->showAddModal = true;
        $this->reset(['predecessorId', 'successorId', 'type', 'lagDays']);
        $this->loadDependencies();
    }

    public function closeAddModal()
    {
        $this->showAddModal = false;
    }

    public function addDependency()
    {
        $this->validate([
            'predecessorId' => 'required|exists:milestones,id',
            'successorId' => 'required|exists:milestones,id|different:predecessorId',
            'type' => 'required|in:fs,ss,ff,sf',
            'lagDays' => 'nullable|integer|min:0',
        ]);

        try {
            $predecessor = Milestone::findOrFail($this->predecessorId);
            $successor = Milestone::findOrFail($this->successorId);

            $service = new DependencyService();
            $service->createDependency($predecessor, $successor, $this->type, $this->lagDays);

            $this->loadDependencies();
            $this->closeAddModal();
            session()->flash('success', 'Dependencia creada correctamente');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function removeDependency($dependencyId)
    {
        $dependency = MilestoneDependency::findOrFail($dependencyId);
        $dependency->delete();
        $this->loadDependencies();
        session()->flash('success', 'Dependencia eliminada correctamente');
    }

    public function render()
    {
        return view('livewire.roadmaps.dependency-manager');
    }
}
