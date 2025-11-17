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
            
            // Verificar que la sección pertenece al plan
            if ($section->plan_id !== $this->plan->id) {
                session()->flash('error', 'La sección no pertenece a este plan.');
                return;
            }
            
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

        // Recargar el plan para actualizar las relaciones
        $this->plan->refresh();
        $this->closeModal();
        $this->dispatch('sectionUpdated');
        
        // Redirigir para recargar la página y actualizar las pestañas
        return redirect()->route('plans.show', $this->plan)
            ->with('success', $this->sectionId ? 'Sección actualizada correctamente.' : 'Sección creada correctamente.');
    }

    public function delete($sectionId)
    {
        $section = PlanSection::findOrFail($sectionId);
        
        // Verificar que la sección pertenece al plan
        if ($section->plan_id !== $this->plan->id) {
            session()->flash('error', 'La sección no pertenece a este plan.');
            return;
        }
        
        if (!Auth::user()->can('update', $this->plan)) {
            session()->flash('error', 'No tienes permisos para eliminar secciones.');
            return;
        }

        // No permitir eliminar secciones requeridas
        if ($section->is_required) {
            session()->flash('error', 'No se puede eliminar una sección requerida.');
            return;
        }

        $section->delete();
        
        // Recargar el plan para actualizar las relaciones
        $this->plan->refresh();
        $this->dispatch('sectionUpdated');
        
        // Redirigir para recargar la página y actualizar las pestañas
        return redirect()->route('plans.show', $this->plan)
            ->with('success', 'Sección eliminada correctamente.');
    }

    protected $listeners = ['sectionUpdated' => '$refresh'];

    public function render()
    {
        // Recargar el plan para obtener las secciones actualizadas
        $this->plan->refresh();
        $sections = $this->plan->sections()->orderBy('order')->get();
        
        return view('livewire.plans.plan-section-editor', [
            'sections' => $sections,
        ]);
    }
}

