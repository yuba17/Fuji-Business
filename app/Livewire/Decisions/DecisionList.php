<?php

namespace App\Livewire\Decisions;

use App\Models\Decision;
use App\Models\Plan;
use Livewire\Component;
use Livewire\WithPagination;

class DecisionList extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $planId = '';
    public $plans = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'planId' => ['except' => ''],
    ];

    public function mount()
    {
        $this->plans = Plan::where('status', '!=', 'archived')->get();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingPlanId()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->status = '';
        $this->planId = '';
        $this->resetPage();
    }

    public function render()
    {
        $query = Decision::with(['proponent', 'plans']);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->planId) {
            $query->whereHas('plans', function($q) {
                $q->where('plans.id', $this->planId);
            });
        }

        $decisions = $query->latest('decision_date')->paginate(15);

        return view('livewire.decisions.decision-list', [
            'decisions' => $decisions,
        ]);
    }
}
