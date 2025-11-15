<?php

namespace App\Livewire\Decisions;

use App\Models\Decision;
use App\Models\Plan;
use App\Models\User;
use Livewire\Component;

class DecisionEditor extends Component
{
    public $decisionId = null;
    public $title = '';
    public $description = '';
    public $decision_date = '';
    public $impact_type = '';
    public $status = 'proposed';
    public $alternatives_considered = '';
    public $rationale = '';
    public $expected_impact = '';
    public $planIds = [];
    public $plans = [];
    public $users = [];
    public $showModal = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'decision_date' => 'nullable|date',
        'impact_type' => 'nullable|in:strategic,operational,commercial,hr,economic',
        'status' => 'required|in:proposed,discussion,pending_approval,approved,rejected,implemented,reviewed',
        'alternatives_considered' => 'nullable|string',
        'rationale' => 'nullable|string',
        'expected_impact' => 'nullable|string',
        'planIds' => 'nullable|array',
    ];

    public function mount($decision = null)
    {
        $this->plans = Plan::where('status', '!=', 'archived')->get();
        $this->users = User::all();

        if ($decision) {
            $this->decisionId = $decision->id;
            $this->title = $decision->title;
            $this->description = $decision->description ?? '';
            $this->decision_date = $decision->decision_date?->format('Y-m-d') ?? '';
            $this->impact_type = $decision->impact_type ?? '';
            $this->status = $decision->status;
            $this->alternatives_considered = $decision->alternatives_considered ?? '';
            $this->rationale = $decision->rationale ?? '';
            $this->expected_impact = $decision->expected_impact ?? '';
            $this->planIds = $decision->plans->pluck('id')->toArray();
        } else {
            $this->decision_date = now()->format('Y-m-d');
        }
    }

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['decisionId', 'title', 'description', 'decision_date', 'impact_type', 'status', 'alternatives_considered', 'rationale', 'expected_impact', 'planIds']);
        $this->decision_date = now()->format('Y-m-d');
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'decision_date' => $this->decision_date ?: now(),
            'impact_type' => $this->impact_type,
            'status' => $this->status,
            'alternatives_considered' => $this->alternatives_considered,
            'rationale' => $this->rationale,
            'expected_impact' => $this->expected_impact,
        ];

        if ($this->decisionId) {
            $decision = Decision::findOrFail($this->decisionId);
            $decision->update($data);
            $decision->plans()->sync($this->planIds);
            session()->flash('success', 'Decisión actualizada correctamente');
        } else {
            $data['proponent_id'] = auth()->id();
            $decision = Decision::create($data);
            $decision->plans()->sync($this->planIds);
            session()->flash('success', 'Decisión creada correctamente');
        }

        $this->closeModal();
        $this->dispatch('decision-saved');
        return redirect()->route('decisions.show', $decision);
    }

    public function render()
    {
        return view('livewire.decisions.decision-editor');
    }
}
