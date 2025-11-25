<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use App\Models\Plan;
use App\Models\Area;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskKanban extends Component
{
    public $planId = null;
    public $areaId = null;
    public $selectedPlan = null;
    public $selectedArea = null;
    // Optimización: Cachear planes y áreas para evitar recargar en cada render
    protected $plans = null;
    protected $areas = null;
    
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
        
        // Optimización: Cargar planes y áreas una sola vez al montar
        $this->plans = Plan::where('status', '!=', 'archived')->select('id', 'name')->orderBy('name')->get();
        $this->areas = Area::where('is_active', true)->select('id', 'name')->orderBy('name')->get();
    }

    public function updateTaskStatus($taskId, $newStatus, $newOrder = null)
    {
        $task = Task::findOrFail($taskId);
        
        // Verificar permisos
        if (!Auth::user()->can('update', $task)) {
            session()->flash('error', 'No tienes permisos para actualizar esta tarea');
            return;
        }
        
        $oldStatus = $task->status;
        
        // Si no se especifica orden, ponerlo al final de la nueva columna
        if ($newOrder === null) {
            $maxOrder = Task::where('status', $newStatus)
                ->where('id', '!=', $taskId)
                ->max('order') ?? 0;
            $newOrder = $maxOrder + 1;
        }
        
        // Si cambió de columna, reordenar la columna antigua
        if ($oldStatus !== $newStatus) {
            Task::where('status', $oldStatus)
                ->where('id', '!=', $taskId)
                ->where('order', '>', $task->order)
                ->decrement('order');
        }
        
        // Reordenar la columna nueva
        Task::where('status', $newStatus)
            ->where('id', '!=', $taskId)
            ->where('order', '>=', $newOrder)
            ->increment('order');
        
        // Actualizar estado y orden
        $task->update([
            'status' => $newStatus,
            'order' => $newOrder,
        ]);
        
        $this->dispatch('taskUpdated');
        session()->flash('success', 'Tarea actualizada correctamente');
    }

    public function updateTaskOrder($taskId, $newStatus, $newOrder, $oldStatus = null)
    {
        $task = Task::findOrFail($taskId);
        
        if (!Auth::user()->can('update', $task)) {
            session()->flash('error', 'No tienes permisos para actualizar esta tarea');
            return;
        }
        
        $oldStatus = $oldStatus ?? $task->status;
        
        // Si cambió de columna, reordenar la columna antigua
        if ($oldStatus !== $newStatus) {
            Task::where('status', $oldStatus)
                ->where('id', '!=', $taskId)
                ->where('order', '>', $task->order)
                ->decrement('order');
        }
        
        // Reordenar la columna nueva
        Task::where('status', $newStatus)
            ->where('id', '!=', $taskId)
            ->where('order', '>=', $newOrder)
            ->increment('order');
        
        // Actualizar estado y orden de la tarea
        $task->update([
            'status' => $newStatus,
            'order' => $newOrder,
        ]);
        
        $this->dispatch('taskUpdated');
        session()->flash('success', 'Tarea actualizada correctamente');
    }

    public function getTasksProperty()
    {
        $user = Auth::user();
        
        // Optimización: No cargar subtasks por defecto (solo si se necesitan)
        $query = Task::with(['plan', 'area', 'assignedUser', 'milestone'])
            ->whereNull('parent_task_id') // Solo mostrar tareas principales, no subtareas
            ->orderBy('order', 'asc')
            ->orderBy('created_at', 'asc');
        
        // Filtrar según rol
        if ($user->isManager()) {
            // Optimización: Usar pluck directo en lugar de cargar relación completa
            $userAreaIds = $user->areas()->pluck('areas.id')->toArray();
            
            $query->where(function($q) use ($user, $userAreaIds) {
                $q->where('assigned_to', $user->id)
                  ->orWhere('created_by', $user->id);
                  
                if (!empty($userAreaIds)) {
                    $q->orWhereIn('area_id', $userAreaIds);
                }
                
                // Revertir a whereHas: puede ser más rápido con índices adecuados y Eloquent lo optimiza
                $q->orWhereHas('plan', function($planQ) use ($user) {
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
        // Optimización: Usar planes y áreas cacheados en mount
        return view('livewire.tasks.task-kanban', [
            'tasks' => $this->tasks,
            'plans' => $this->plans,
            'areas' => $this->areas,
        ]);
    }
}
