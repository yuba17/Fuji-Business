<?php

namespace App\Livewire\Plans;

use App\Models\Plan;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class PlanStatusChanger extends Component
{
    public Plan $plan;
    public $showModal = false;
    public $newStatus = '';
    public $comment = '';

    protected $rules = [
        'newStatus' => 'required|in:draft,internal_review,director_review,approved,in_progress,under_review,closed,archived',
        'comment' => 'nullable|string|max:500',
    ];

    public function mount(Plan $plan)
    {
        $this->plan = $plan;
    }

    public function openModal()
    {
        $this->showModal = true;
        $this->newStatus = $this->plan->status;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['newStatus', 'comment']);
    }

    public function getAvailableStatusesProperty()
    {
        $user = Auth::user();
        $currentStatus = $this->plan->status;
        
        // Definir transiciones válidas según el estado actual y el rol
        $transitions = [
            'draft' => ['internal_review'],
            'internal_review' => ['draft', 'director_review'],
            'director_review' => ['draft', 'internal_review', 'approved'],
            'approved' => ['in_progress', 'closed'],
            'in_progress' => ['under_review', 'closed'],
            'under_review' => ['in_progress', 'closed'],
            'closed' => ['archived'],
            'archived' => [], // No se puede cambiar desde archivado
        ];

        $available = $transitions[$currentStatus] ?? [];
        
        // Agregar el estado actual para poder mantenerlo
        if (!in_array($currentStatus, $available)) {
            $available[] = $currentStatus;
        }

        // Filtrar según permisos del usuario
        $filtered = [];
        foreach ($available as $status) {
            if ($this->canTransitionTo($status, $user)) {
                $filtered[] = $status;
            }
        }

        return $filtered;
    }

    protected function canTransitionTo($status, $user): bool
    {
        // Solo los directores pueden aprobar planes
        if ($status === 'approved' && !$user->isDirector()) {
            return false;
        }

        // Solo los managers y directores pueden enviar a revisión
        if (in_array($status, ['internal_review', 'director_review']) && 
            !$user->isManager() && !$user->isDirector()) {
            return false;
        }

        // Solo el manager del plan o director puede cambiar estados
        if (!$user->isDirector() && 
            $this->plan->manager_id !== $user->id && 
            $this->plan->director_id !== $user->id) {
            return false;
        }

        return true;
    }

    public function updateStatus()
    {
        $this->validate();

        // Verificar que la transición es válida
        if (!in_array($this->newStatus, $this->availableStatuses)) {
            session()->flash('error', 'No puedes cambiar a ese estado desde el estado actual.');
            return;
        }

        // Verificar permisos
        $user = Auth::user();
        if (!$this->canTransitionTo($this->newStatus, $user)) {
            session()->flash('error', 'No tienes permisos para realizar esta acción.');
            return;
        }

        // Validaciones adicionales según el estado
        if ($this->newStatus === 'approved' && !$this->plan->hasRequiredSections()) {
            session()->flash('error', 'El plan debe tener al menos una sección antes de ser aprobado.');
            return;
        }

        if ($this->newStatus === 'in_progress' && !$this->plan->isApproved()) {
            session()->flash('error', 'El plan debe estar aprobado antes de iniciar su ejecución.');
            return;
        }

        // Actualizar el estado
        $oldStatus = $this->plan->status;
        $this->plan->update(['status' => $this->newStatus]);

        // Registrar el cambio en metadata si existe
        $metadata = $this->plan->metadata ?? [];
        if (!isset($metadata['status_history'])) {
            $metadata['status_history'] = [];
        }
        $metadata['status_history'][] = [
            'from' => $oldStatus,
            'to' => $this->newStatus,
            'user_id' => $user->id,
            'user_name' => $user->name,
            'comment' => $this->comment,
            'changed_at' => now()->toIso8601String(),
        ];
        $this->plan->update(['metadata' => $metadata]);

        $this->closeModal();
        $this->dispatch('statusUpdated');
        $this->dispatch('$refresh');
        session()->flash('success', 'Estado del plan actualizado correctamente.');
    }

    public function getStatusOptionsProperty()
    {
        return [
            'draft' => 'Borrador',
            'internal_review' => 'En Revisión Interna',
            'director_review' => 'En Revisión Dirección',
            'approved' => 'Aprobado',
            'in_progress' => 'En Ejecución',
            'under_review' => 'En Revisión Periódica',
            'closed' => 'Cerrado',
            'archived' => 'Archivado',
        ];
    }

    public function render()
    {
        return view('livewire.plans.plan-status-changer');
    }
}

