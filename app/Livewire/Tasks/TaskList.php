<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use App\Models\Plan;
use App\Models\Area;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class TaskList extends Component
{
    use WithPagination;

    public $search = '';
    public $planId = null;
    public $areaId = null;
    public $status = '';
    public $priority = '';
    public $assignedTo = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'planId' => ['except' => ''],
        'areaId' => ['except' => ''],
        'status' => ['except' => ''],
        'priority' => ['except' => ''],
        'assignedTo' => ['except' => ''],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    protected $listeners = ['taskUpdated' => '$refresh', 'taskDeleted' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPlanId()
    {
        $this->resetPage();
    }

    public function updatingAreaId()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingPriority()
    {
        $this->resetPage();
    }

    public function updatingAssignedTo()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->planId = null;
        $this->areaId = null;
        $this->status = '';
        $this->priority = '';
        $this->assignedTo = '';
        $this->sortBy = 'created_at';
        $this->sortDirection = 'desc';
        $this->resetPage();
    }

    public function render()
    {
        $user = Auth::user();
        
        $query = Task::with(['plan', 'area', 'assignedUser', 'milestone', 'createdBy']);
        
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
        } elseif ($user->isVisualizacion()) {
            // Visualización ve todas las tareas
        }
        
        // Aplicar filtros
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }
        
        if ($this->planId) {
            $query->where('plan_id', $this->planId);
        }
        
        if ($this->areaId) {
            $query->where('area_id', $this->areaId);
        }
        
        if ($this->status) {
            $query->where('status', $this->status);
        }
        
        if ($this->priority) {
            $query->where('priority', $this->priority);
        }
        
        if ($this->assignedTo) {
            $query->where('assigned_to', $this->assignedTo);
        }
        
        // Ordenamiento
        $query->orderBy($this->sortBy, $this->sortDirection);
        
        $tasks = $query->paginate(15);
        
        // Datos para los filtros
        $plans = Plan::whereHas('tasks')->orderBy('name')->get();
        $areas = Area::whereHas('tasks')->orderBy('name')->get();
        $users = User::whereHas('assignedTasks')->orderBy('name')->get();
        
        return view('livewire.tasks.task-list', [
            'tasks' => $tasks,
            'plans' => $plans,
            'areas' => $areas,
            'users' => $users,
        ]);
    }
}
