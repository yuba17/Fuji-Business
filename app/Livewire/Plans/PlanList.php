<?php

namespace App\Livewire\Plans;

use App\Models\Plan;
use App\Models\PlanType;
use App\Models\Area;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlanList extends Component
{
    use WithPagination;

    public $search = '';
    public $planTypeId = '';
    public $status = '';
    public $areaId = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'planTypeId' => ['except' => ''],
        'status' => ['except' => ''],
        'areaId' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPlanTypeId()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingAreaId()
    {
        $this->resetPage();
    }

    public function render()
    {
        $user = Auth::user();
        
        $query = Plan::with(['planType', 'area', 'director', 'manager'])
            ->withCount(['tasks', 'kpis'])
            ->orderBy('created_at', 'desc');
        
        // Filtrar según rol
        if ($user->isManager()) {
            // Optimización: Usar pluck directo en lugar de cargar relación completa
            $userAreaIds = $user->areas()->pluck('areas.id')->toArray();
            
            $query->where(function($q) use ($user, $userAreaIds) {
                $q->where('manager_id', $user->id)
                  ->orWhere('director_id', $user->id);
                  
                if (!empty($userAreaIds)) {
                    $q->orWhereIn('area_id', $userAreaIds);
                }
            });
        } elseif ($user->isTecnico()) {
            // Los técnicos solo ven planes donde están asignados a tareas
            // Revertir a whereHas: puede ser más rápido con índices adecuados
            $query->whereHas('tasks', function($taskQ) use ($user) {
                $taskQ->where('assigned_to', $user->id);
            });
        }
        
        // Aplicar filtros
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }
        
        if ($this->planTypeId) {
            $query->where('plan_type_id', $this->planTypeId);
        }
        
        if ($this->status) {
            $query->where('status', $this->status);
        }
        
        if ($this->areaId) {
            $query->where('area_id', $this->areaId);
        }
        
        $plans = $query->paginate(12);
        
        return view('livewire.plans.plan-list', [
            'plans' => $plans,
            'planTypes' => PlanType::where('is_active', true)->get(),
            'areas' => Area::where('is_active', true)->get(),
        ]);
    }
}

