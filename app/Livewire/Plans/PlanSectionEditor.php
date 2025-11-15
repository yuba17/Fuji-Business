<?php

namespace App\Livewire\Plans;

use App\Models\Plan;
use App\Models\PlanSection;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class PlanSectionEditor extends Component
{
    public Plan $plan;
    public $sectionId = null;
    public $title = '';
    public $content = '';
    public $type = 'text';
    public $isRequired = false;
    public $showModal = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'content' => 'nullable|string',
        'type' => 'required|in:text,html,markdown',
        'isRequired' => 'boolean',
    ];

    public function mount(Plan $plan)
    {
        $this->plan = $plan;
    }

    public function openModal($sectionId = null)
    {
        $this->sectionId = $sectionId;
        
        if ($sectionId) {
            $section = PlanSection::findOrFail($sectionId);
            $this->title = $section->title;
            $this->content = $section->content;
            $this->type = $section->type;
            $this->isRequired = $section->is_required;
        } else {
            $this->reset(['title', 'content', 'type', 'isRequired']);
        }
        
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['sectionId', 'title', 'content', 'type', 'isRequired']);
    }

    public function save()
    {
        $this->validate();

        if (!Auth::user()->can('update', $this->plan)) {
            session()->flash('error', 'No tienes permisos para editar este plan.');
            return;
        }

        if ($this->sectionId) {
            $section = PlanSection::findOrFail($this->sectionId);
            $section->update([
                'title' => $this->title,
                'content' => $this->content,
                'type' => $this->type,
                'is_required' => $this->isRequired,
            ]);
            session()->flash('success', 'Sección actualizada correctamente.');
        } else {
            $maxOrder = PlanSection::where('plan_id', $this->plan->id)->max('order') ?? 0;
            PlanSection::create([
                'plan_id' => $this->plan->id,
                'title' => $this->title,
                'content' => $this->content,
                'type' => $this->type,
                'is_required' => $this->isRequired,
                'order' => $maxOrder + 1,
            ]);
            session()->flash('success', 'Sección creada correctamente.');
        }

        $this->closeModal();
        $this->dispatch('sectionUpdated');
    }

    public function delete($sectionId)
    {
        $section = PlanSection::findOrFail($sectionId);
        
        if (!Auth::user()->can('update', $this->plan)) {
            session()->flash('error', 'No tienes permisos para eliminar secciones.');
            return;
        }

        $section->delete();
        session()->flash('success', 'Sección eliminada correctamente.');
        $this->dispatch('sectionUpdated');
    }

    public function render()
    {
        $sections = $this->plan->sections()->orderBy('order')->get();
        
        return view('livewire.plans.plan-section-editor', [
            'sections' => $sections,
        ]);
    }
}

