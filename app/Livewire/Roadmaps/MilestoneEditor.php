<?php

namespace App\Livewire\Roadmaps;

use App\Models\Milestone;
use App\Models\Plan;
use App\Models\User;
use Livewire\Component;

class MilestoneEditor extends Component
{
    public $milestoneId = null;
    public $planId;
    public $name = '';
    public $description = '';
    public $start_date = '';
    public $end_date = '';
    public $target_date = '';
    public $status = 'not_started';
    public $responsible_id = '';
    public $progress_percentage = 0;
    public $order = 0;
    public $showModal = false;
    public $plan;
    public $users = [];
    public $availableMilestones = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'target_date' => 'required|date',
        'status' => 'required|in:not_started,in_progress,completed,delayed,cancelled',
        'responsible_id' => 'nullable|exists:users,id',
        'progress_percentage' => 'nullable|integer|min:0|max:100',
        'order' => 'nullable|integer|min:0',
    ];

    public function mount($plan, $milestone = null)
    {
        $this->plan = is_object($plan) ? $plan : Plan::findOrFail($plan);
        $this->planId = $this->plan->id;
        // Optimización: Solo seleccionar campos necesarios y ordenar por nombre
        $this->users = User::select('id', 'name')->orderBy('name')->get();
        $this->availableMilestones = $this->plan->milestones()->orderBy('name')->get();

        if ($milestone) {
            $this->milestoneId = is_object($milestone) ? $milestone->id : $milestone;
            $milestone = Milestone::findOrFail($this->milestoneId);
            $this->name = $milestone->name;
            $this->description = $milestone->description ?? '';
            $this->start_date = $milestone->start_date?->format('Y-m-d') ?? '';
            $this->end_date = $milestone->end_date?->format('Y-m-d') ?? '';
            $this->target_date = $milestone->target_date->format('Y-m-d');
            $this->status = $milestone->status;
            $this->responsible_id = $milestone->responsible_id ?? '';
            $this->progress_percentage = $milestone->progress_percentage ?? 0;
            $this->order = $milestone->order ?? 0;
        } else {
            $this->target_date = now()->format('Y-m-d');
        }
    }

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['milestoneId', 'name', 'description', 'start_date', 'end_date', 'target_date', 'status', 'responsible_id', 'progress_percentage', 'order']);
        $this->target_date = now()->format('Y-m-d');
        $this->status = 'not_started';
    }

    public function save()
    {
        $this->validate();

        $data = [
            'plan_id' => $this->planId,
            'name' => $this->name,
            'description' => $this->description,
            'start_date' => $this->start_date ?: null,
            'end_date' => $this->end_date ?: null,
            'target_date' => $this->target_date,
            'status' => $this->status,
            'responsible_id' => $this->responsible_id ?: null,
            'progress_percentage' => $this->progress_percentage ?? 0,
            'order' => $this->order ?? 0,
        ];

        if ($this->milestoneId) {
            $milestone = Milestone::findOrFail($this->milestoneId);
            $milestone->update($data);
            session()->flash('success', 'Hito actualizado correctamente');
        } else {
            $milestone = Milestone::create($data);
            session()->flash('success', 'Hito creado correctamente');
        }

        $this->closeModal();
        $this->dispatch('milestone-saved');
        return redirect()->route('plans.roadmap', $this->plan);
    }

    public function render()
    {
        return view('livewire.roadmaps.milestone-editor');
    }
}
