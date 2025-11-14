<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use App\Models\Plan;
use App\Models\Area;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class TaskKanban extends Component
{
    public $planId = null;
    public $areaId = null;
    public $selectedPlan = null;
    public $selectedArea = null;
    
    public $columns = [
        'todo' => ['label' => 'Por Hacer', 'color' => 'gray'],
        'in_progress' => ['label' => 'En Progreso', 'color' => 'blue'],
        'review' => ['label' => 'En Revisión', 'color' => 'yellow'],
        'done' => ['label' => 'Completadas', 'color' => 'green'],
    ];

    protected $listeners = ['taskUpdated' => '$refresh', 'taskDeleted' => '$refresh'];

    public function mount($planId = null, $areaId = null)
    {
        $this->planId = $planId;
        $this->areaId = $areaId;
        
        if ($planId) {
            $this->selectedPlan = Plan::find($planId);
        }
        
        if ($areaId) {
            $this->selectedArea = Area::find($areaId);
        }
    }

    public function updateTaskStatus($taskId, $newStatus)
    {
        $task = Task::findOrFail($taskId);
        
        // Verificar permisos
        if (!Auth::user()->can('update', $task)) {
            session()->flash('error', 'No tienes permisos para actualizar esta tarea');
            return;
        }
        
        $task->update(['status' => $newStatus]);
        
        $this->dispatch('taskUpdated');
        session()->flash('success', 'Tarea actualizada correctamente');
    }

    public function updateTaskOrder($taskId, $newStatus, $newOrder)
    {
        $task = Task::findOrFail($taskId);
        
        if (!Auth::user()->can('update', $task)) {
            return;
        }
        
        // Actualizar estado y orden
        $task->update([
            'status' => $newStatus,
            'order' => $newOrder,
        ]);
        
        // Reordenar otras tareas en la misma columna
        Task::where('status', $newStatus)
            ->where('id', '!=', $taskId)
            ->where('order', '>=', $newOrder)
            ->increment('order');
    }

    public function getTasksProperty()
    {
        $user = Auth::user();
        
        $query = Task::with(['plan', 'area', 'assignedUser', 'milestone'])
            ->orderBy('order')
            ->orderBy('created_at');
        
        // Filtrar según rol
        if ($user->isManager()) {
            $query->where(function($q) use ($user) {
                $q->where('assigned_to', $user->id)
                  ->orWhere('created_by', $user->id)
                  ->orWhereIn('area_id', $user->areas->pluck('id'))
                  ->orWhereHas('plan', function($planQ) use ($user) {
                      $planQ->where('manager_id', $user->id)
                            ->orWhere('director_id', $user->id);
                  });
            });
        } elseif ($user->isTecnico()) {
            $query->where(function($q) use ($user) {
                $q->where('assigned_to', $user->id)
                  ->orWhere('created_by', $user->id);
            });
        }
        
        // Filtros adicionales
        if ($this->planId) {
            $query->where('plan_id', $this->planId);
        }
        
        if ($this->areaId) {
            $query->where('area_id', $this->areaId);
        }
        
        $tasks = $query->get();
        
        // Agrupar por estado
        $grouped = [];
        foreach ($this->columns as $status => $column) {
            $grouped[$status] = $tasks->where('status', $status)->values();
        }
        
        return $grouped;
    }

    public function render()
    {
        return view('livewire.tasks.task-kanban', [
            'tasks' => $this->tasks,
            'plans' => Plan::where('status', '!=', 'archived')->get(),
            'areas' => Area::where('is_active', true)->get(),
        ]);
    }
}
