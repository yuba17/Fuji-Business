<?php

namespace App\Livewire\Plans;

use App\Models\Plan;
use App\Models\Infrastructure;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class PlanDesarrolloInfraestructura extends Component
{
    use WithPagination;

    public Plan $plan;
    
    // Filtros
    public $search = '';
    public $type = '';
    public $category = '';
    public $status = '';
    public $provider = '';
    public $ownerId = '';
    public $showCriticalOnly = false;
    public $showAlertsOnly = false;
    
    // Vista
    public $viewMode = 'inventory'; // inventory, capacity, roadmap, costs, analysis
    
    // Modal
    public $showInfrastructureModal = false;
    
    // Formulario
    public $infrastructureId = null;
    public $infrastructureName = '';
    public $infrastructureDescription = '';
    public $infrastructureType = '';
    public $infrastructureCategory = '';
    public $infrastructureStatus = 'active';
    public $infrastructureOwnerId = null;
    public $infrastructureCostMonthly = null;
    public $infrastructureCostYearly = null;
    public $infrastructureCapacity = '';
    public $infrastructureUtilizationPercent = null;
    public $infrastructureProvider = '';
    public $infrastructureLocation = '';
    public $infrastructureRoadmapDate = null;
    public $infrastructureIsCritical = false;

    protected $listeners = ['infrastructureUpdated' => '$refresh'];

    public function mount(Plan $plan)
    {
        $this->plan = $plan;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingType()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function openInfrastructureModal($infrastructureId = null)
    {
        $this->infrastructureId = $infrastructureId;
        
        if ($infrastructureId) {
            $infrastructure = Infrastructure::find($infrastructureId);
            $this->infrastructureName = $infrastructure->name;
            $this->infrastructureDescription = $infrastructure->description ?? '';
            $this->infrastructureType = $infrastructure->type;
            $this->infrastructureCategory = $infrastructure->category;
            $this->infrastructureStatus = $infrastructure->status;
            $this->infrastructureOwnerId = $infrastructure->owner_id;
            $this->infrastructureCostMonthly = $infrastructure->cost_monthly;
            $this->infrastructureCostYearly = $infrastructure->cost_yearly;
            $this->infrastructureCapacity = $infrastructure->capacity ?? '';
            $this->infrastructureUtilizationPercent = $infrastructure->utilization_percent;
            $this->infrastructureProvider = $infrastructure->provider ?? '';
            $this->infrastructureLocation = $infrastructure->location ?? '';
            $this->infrastructureRoadmapDate = $infrastructure->roadmap_date?->format('Y-m-d');
            $this->infrastructureIsCritical = $infrastructure->is_critical;
        } else {
            $this->resetInfrastructureForm();
        }
        
        $this->showInfrastructureModal = true;
    }

    public function closeInfrastructureModal()
    {
        $this->showInfrastructureModal = false;
        $this->resetInfrastructureForm();
    }

    public function saveInfrastructure()
    {
        $validated = $this->validate([
            'infrastructureName' => 'required|string|max:255',
            'infrastructureDescription' => 'nullable|string',
            'infrastructureType' => 'required|string|max:255',
            'infrastructureCategory' => 'required|string|max:255',
            'infrastructureStatus' => 'required|in:active,maintenance,deprecated,planned',
            'infrastructureOwnerId' => 'nullable|exists:users,id',
            'infrastructureCostMonthly' => 'nullable|numeric|min:0',
            'infrastructureCostYearly' => 'nullable|numeric|min:0',
            'infrastructureCapacity' => 'nullable|string|max:255',
            'infrastructureUtilizationPercent' => 'nullable|integer|min:0|max:100',
            'infrastructureProvider' => 'nullable|string|max:255',
            'infrastructureLocation' => 'nullable|string|max:255',
            'infrastructureRoadmapDate' => 'nullable|date',
            'infrastructureIsCritical' => 'boolean',
        ]);

        $data = [
            'name' => $validated['infrastructureName'],
            'description' => $validated['infrastructureDescription'],
            'type' => $validated['infrastructureType'],
            'category' => $validated['infrastructureCategory'],
            'status' => $validated['infrastructureStatus'],
            'owner_id' => $validated['infrastructureOwnerId'] ?: null,
            'cost_monthly' => $validated['infrastructureCostMonthly'],
            'cost_yearly' => $validated['infrastructureCostYearly'],
            'capacity' => $validated['infrastructureCapacity'],
            'utilization_percent' => $validated['infrastructureUtilizationPercent'],
            'provider' => $validated['infrastructureProvider'],
            'location' => $validated['infrastructureLocation'],
            'roadmap_date' => $validated['infrastructureRoadmapDate'],
            'is_critical' => $validated['infrastructureIsCritical'],
            'area_id' => $this->plan->area_id,
            'plan_id' => $this->plan->id,
        ];

        if ($this->infrastructureId) {
            Infrastructure::find($this->infrastructureId)->update($data);
            session()->flash('success', 'Infraestructura actualizada correctamente');
        } else {
            Infrastructure::create($data);
            session()->flash('success', 'Infraestructura creada correctamente');
        }

        $this->closeInfrastructureModal();
        $this->dispatch('infrastructureUpdated');
    }

    public function deleteInfrastructure($infrastructureId)
    {
        Infrastructure::find($infrastructureId)->delete();
        session()->flash('success', 'Infraestructura eliminada correctamente');
        $this->dispatch('infrastructureUpdated');
    }

    public function resetInfrastructureForm()
    {
        $this->infrastructureId = null;
        $this->infrastructureName = '';
        $this->infrastructureDescription = '';
        $this->infrastructureType = '';
        $this->infrastructureCategory = '';
        $this->infrastructureStatus = 'active';
        $this->infrastructureOwnerId = null;
        $this->infrastructureCostMonthly = null;
        $this->infrastructureCostYearly = null;
        $this->infrastructureCapacity = '';
        $this->infrastructureUtilizationPercent = null;
        $this->infrastructureProvider = '';
        $this->infrastructureLocation = '';
        $this->infrastructureRoadmapDate = null;
        $this->infrastructureIsCritical = false;
    }

    public function render()
    {
        // Obtener infraestructura del área/plan
        $infrastructuresQuery = Infrastructure::where('area_id', $this->plan->area_id)
            ->where(function($q) {
                $q->where('plan_id', $this->plan->id)
                  ->orWhereNull('plan_id');
            });

        if ($this->search) {
            $infrastructuresQuery->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->type) {
            $infrastructuresQuery->where('type', $this->type);
        }

        if ($this->category) {
            $infrastructuresQuery->where('category', $this->category);
        }

        if ($this->status) {
            $infrastructuresQuery->where('status', $this->status);
        }

        if ($this->showCriticalOnly) {
            $infrastructuresQuery->where('is_critical', true);
        }

        if ($this->provider) {
            $infrastructuresQuery->where('provider', $this->provider);
        }

        if ($this->ownerId) {
            $infrastructuresQuery->where('owner_id', $this->ownerId);
        }

        if ($this->showAlertsOnly) {
            $infrastructuresQuery->where(function($q) {
                $q->where(function($q2) {
                    $q2->where('is_critical', true)->whereNull('owner_id');
                })
                ->orWhere('utilization_percent', '>', 80)
                ->orWhere(function($q2) {
                    $q2->where('status', 'active')->whereNull('owner_id');
                });
            });
        }

        $infrastructures = $infrastructuresQuery->with(['owner', 'area'])
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        // Filtrar alertas si está activo
        if ($this->showAlertsOnly) {
            $infrastructures = $infrastructures->filter(function($infra) {
                return $infra->is_critical && !$infra->owner_id || // Crítico sin propietario
                       ($infra->utilization_percent !== null && $infra->utilization_percent > 80) || // Alta utilización
                       ($infra->status === 'active' && !$infra->owner_id); // Activo sin propietario
            });
        }

        // Calcular estadísticas
        $allInfrastructures = Infrastructure::where('area_id', $this->plan->area_id)
            ->where(function($q) {
                $q->where('plan_id', $this->plan->id)
                  ->orWhereNull('plan_id');
            })
            ->get();

        $stats = [
            'total' => $allInfrastructures->count(),
            'active' => $allInfrastructures->where('status', 'active')->count(),
            'planned' => $allInfrastructures->where('status', 'planned')->count(),
            'critical' => $allInfrastructures->where('is_critical', true)->count(),
            'total_monthly_cost' => $allInfrastructures->sum('cost_monthly') ?? 0,
            'total_yearly_cost' => $allInfrastructures->sum(function($i) {
                if ($i->cost_yearly) return $i->cost_yearly;
                if ($i->cost_monthly) return $i->cost_monthly * 12;
                return 0;
            }),
            'avg_utilization' => $allInfrastructures->whereNotNull('utilization_percent')->avg('utilization_percent') ?? 0,
            'critical_without_owner' => $allInfrastructures->where('is_critical', true)->whereNull('owner_id')->count(),
            'high_utilization' => $allInfrastructures->where('utilization_percent', '>', 80)->count(),
            'without_owner' => $allInfrastructures->whereNull('owner_id')->count(),
        ];

        // Análisis de costes por categoría
        $costsByCategory = $allInfrastructures->groupBy('category')->map(function($items) {
            return [
                'count' => $items->count(),
                'monthly' => $items->sum('cost_monthly') ?? 0,
                'yearly' => $items->sum(function($i) {
                    if ($i->cost_yearly) return $i->cost_yearly;
                    if ($i->cost_monthly) return $i->cost_monthly * 12;
                    return 0;
                }),
            ];
        });

        // Análisis de costes por proveedor
        $costsByProvider = $allInfrastructures->whereNotNull('provider')->groupBy('provider')->map(function($items) {
            return [
                'count' => $items->count(),
                'monthly' => $items->sum('cost_monthly') ?? 0,
                'yearly' => $items->sum(function($i) {
                    if ($i->cost_yearly) return $i->cost_yearly;
                    if ($i->cost_monthly) return $i->cost_monthly * 12;
                    return 0;
                }),
            ];
        });

        // Agrupar por categoría
        $byCategory = $infrastructures->groupBy('category');

        // Agrupar por tipo
        $byType = $infrastructures->groupBy('type');

        // Roadmap (infraestructura planificada)
        $roadmap = $infrastructures->where('status', 'planned')
            ->whereNotNull('roadmap_date')
            ->sortBy('roadmap_date');

        // Tipos y categorías disponibles
        $types = Infrastructure::where('area_id', $this->plan->area_id)
            ->whereNotNull('type')
            ->distinct()
            ->pluck('type')
            ->sort()
            ->values();

        $categories = Infrastructure::where('area_id', $this->plan->area_id)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values();

        $providers = Infrastructure::where('area_id', $this->plan->area_id)
            ->whereNotNull('provider')
            ->distinct()
            ->pluck('provider')
            ->sort()
            ->values();

        // Usuarios del área para asignar como propietarios
        $areaUsers = User::where('area_id', $this->plan->area_id)
            ->orderBy('name')
            ->get();

        return view('livewire.plans.plan-desarrollo-infraestructura', [
            'infrastructures' => $infrastructures,
            'stats' => $stats,
            'byCategory' => $byCategory,
            'byType' => $byType,
            'roadmap' => $roadmap,
            'types' => $types,
            'categories' => $categories,
            'providers' => $providers,
            'costsByCategory' => $costsByCategory,
            'costsByProvider' => $costsByProvider,
            'areaUsers' => $areaUsers,
        ]);
    }
}
