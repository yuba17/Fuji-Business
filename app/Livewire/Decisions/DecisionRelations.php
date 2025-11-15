<?php

namespace App\Livewire\Decisions;

use App\Models\Decision;
use App\Models\Plan;
use Livewire\Component;

class DecisionRelations extends Component
{
    public Decision $decision;
    public $availablePlans = [];
    public $selectedPlanIds = [];
    public $showAddModal = false;

    public function mount(Decision $decision)
    {
        $this->decision = $decision;
        $this->loadRelations();
    }

    public function loadRelations()
    {
        $this->decision->load('plans');
        $this->selectedPlanIds = $this->decision->plans->pluck('id')->toArray();
        $this->availablePlans = Plan::where('status', '!=', 'archived')
            ->whereNotIn('id', $this->selectedPlanIds)
            ->get();
    }

    public function openAddModal()
    {
        $this->showAddModal = true;
        $this->loadRelations();
    }

    public function closeAddModal()
    {
        $this->showAddModal = false;
    }

    public function addPlan($planId)
    {
        if (!in_array($planId, $this->selectedPlanIds)) {
            $this->selectedPlanIds[] = $planId;
            $this->decision->plans()->attach($planId);
            $this->loadRelations();
            session()->flash('success', 'Plan relacionado correctamente');
        }
    }

    public function removePlan($planId)
    {
        $this->decision->plans()->detach($planId);
        $this->loadRelations();
        session()->flash('success', 'Relación eliminada correctamente');
    }

    public function render()
    {
        return view('livewire.decisions.decision-relations');
    }
}
