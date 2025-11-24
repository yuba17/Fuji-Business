<?php

namespace App\Livewire\Plans;

use App\Models\Plan;
use App\Models\Infrastructure;
use App\Models\User;
use App\Models\InfrastructureAttribute;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PlanDesarrolloInfraestructura extends Component
{
    use WithPagination;

    public Plan $plan;
    
    protected int $defaultTaxRate = 21;

    // Filtros
    public $search = '';
    public $type = '';
    public $category = '';
    public $filterInfrastructureClass = ''; // 'license' o 'hardware' (para filtros)
    public $acquisitionStatus = '';
    public $status = '';
    public $provider = '';
    public $ownerId = '';
    public $showCriticalOnly = false;
    public $showAlertsOnly = false;
    public $showExpiringLicenses = false;
    public array $attributeOptions = [];
    
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
    public $infrastructureClass = 'hardware'; // 'license' o 'hardware' (para formulario)
    public $infrastructureAcquisitionStatus = 'purchased'; // 'purchased', 'to_purchase', 'planned'
    public $infrastructureStatus = 'active';
    public $infrastructureOwnerId = null;
    public $infrastructureCostMonthly = null;
    public $infrastructureCostYearly = null;
    public $infrastructureCapacity = '';
    public $infrastructureUtilizationPercent = null;
    public $infrastructureProvider = '';
    public $infrastructureLocation = '';
    public $infrastructureRoadmapDate = null;
    public $infrastructureExpiresAt = null;
    public $infrastructureRenewalReminderDays = 30;
    public $infrastructureTaxRate = 21;
    public $infrastructureIsCritical = false;
    public $trackingMode = 'individual';
    public $quantityTotal = 1;
    public $quantityInUse = 0;
    public $quantityReserved = 0;

    protected $listeners = ['infrastructureUpdated' => '$refresh'];

    public function updatedInfrastructureClass()
    {
        // No resetear el tipo, solo si está vacío o no coincide con ninguna opción disponible
        // Esto permite mantener la selección si el usuario cambia de clase y vuelve
        if (!empty($this->infrastructureType)) {
            $availableTypes = array_keys($this->attributeOptions['type'] ?? []);
            if (!in_array($this->infrastructureType, $availableTypes)) {
                $this->infrastructureType = '';
            }
        }
    }

    public function updatedTrackingMode()
    {
        if ($this->trackingMode === 'individual') {
            $this->quantityTotal = 1;
            $this->quantityInUse = 0;
            $this->quantityReserved = 0;
        } else {
            $this->quantityTotal = max(1, $this->quantityTotal);
        }
    }

    public function mount(Plan $plan)
    {
        $this->plan = $plan;
        $this->attributeOptions = $this->loadAttributeOptions();
    }

    protected function attributeTypeMap(): array
    {
        return [
            'class' => 'class',
            'acquisition_status' => 'acquisition_status',
            'type' => 'type',
            'category' => 'category',
            'operational_status' => 'operational_status',
            'provider' => 'provider',
        ];
    }

    protected function loadAttributeOptions(): array
    {
        // Cachear las opciones por 5 minutos para mejorar rendimiento
        return Cache::remember('infrastructure_attribute_options', 300, function () {
            $options = [];
            foreach ($this->attributeTypeMap() as $key => $type) {
                $options[$key] = InfrastructureAttribute::optionsFor($type);
            }
            return $options;
        });
    }

    public function refreshAttributeOptions(): void
    {
        Cache::forget('infrastructure_attribute_options');
        $this->attributeOptions = $this->loadAttributeOptions();
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

    public function updatingFilterInfrastructureClass()
    {
        $this->resetPage();
    }

    public function updatingAcquisitionStatus()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function openInfrastructureModal($infrastructureId = null)
    {
        // Asegurar que las opciones estén cargadas
        if (empty($this->attributeOptions)) {
            $this->attributeOptions = $this->loadAttributeOptions();
        }
        
        $this->infrastructureId = $infrastructureId;
        
        if ($infrastructureId) {
            $infrastructure = Infrastructure::find($infrastructureId);
            if (!$infrastructure) {
                session()->flash('error', 'Infraestructura no encontrada');
                return;
            }
            $this->infrastructureName = $infrastructure->name;
            $this->infrastructureDescription = $infrastructure->description ?? '';
            $this->infrastructureType = $infrastructure->type;
            $this->infrastructureCategory = $infrastructure->category;
            $this->infrastructureClass = $infrastructure->infrastructure_class ?? 'hardware';
            $this->infrastructureAcquisitionStatus = $infrastructure->acquisition_status ?? 'purchased';
            $this->infrastructureStatus = $infrastructure->status;
            $this->infrastructureOwnerId = $infrastructure->owner_id;
            $this->infrastructureCostMonthly = $infrastructure->cost_monthly;
            $this->infrastructureCostYearly = $infrastructure->cost_yearly;
            $this->infrastructureTaxRate = $infrastructure->tax_rate ?? $this->defaultTaxRate;
            $this->infrastructureCapacity = $infrastructure->capacity ?? '';
            $this->infrastructureUtilizationPercent = $infrastructure->utilization_percent;
            $this->infrastructureProvider = $infrastructure->provider ?? '';
            $this->infrastructureLocation = $infrastructure->location ?? '';
            $this->infrastructureRoadmapDate = $infrastructure->roadmap_date?->format('Y-m-d');
            $this->infrastructureExpiresAt = $infrastructure->expires_at?->format('Y-m-d');
            $this->infrastructureRenewalReminderDays = $infrastructure->renewal_reminder_days ?? 30;
            $this->infrastructureIsCritical = $infrastructure->is_critical;
            $this->trackingMode = $infrastructure->tracking_mode ?? 'individual';
            $this->quantityTotal = $infrastructure->quantity ?? 1;
            $this->quantityInUse = $infrastructure->quantity_in_use ?? 0;
            $this->quantityReserved = $infrastructure->quantity_reserved ?? 0;
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
        // Obtener opciones válidas dinámicamente
        $validClasses = array_keys($this->attributeOptions['class'] ?? []);
        $validAcquisitionStatuses = array_keys($this->attributeOptions['acquisition_status'] ?? []);
        $validStatuses = array_keys($this->attributeOptions['operational_status'] ?? []);
        
        // Si no hay opciones configuradas, usar valores por defecto
        if (empty($validClasses)) {
            $validClasses = ['license', 'hardware'];
        }
        if (empty($validAcquisitionStatuses)) {
            $validAcquisitionStatuses = ['purchased', 'to_purchase', 'planned'];
        }
        if (empty($validStatuses)) {
            $validStatuses = ['active', 'maintenance', 'deprecated', 'planned'];
        }
        
        $validated = $this->validate([
            'infrastructureName' => 'required|string|max:255',
            'infrastructureDescription' => 'nullable|string',
            'infrastructureType' => 'required|string|max:255',
            'infrastructureCategory' => 'required|string|max:255',
            'infrastructureClass' => 'required|in:' . implode(',', $validClasses),
            'infrastructureAcquisitionStatus' => 'required|in:' . implode(',', $validAcquisitionStatuses),
            'infrastructureStatus' => 'required|in:' . implode(',', $validStatuses),
            'infrastructureOwnerId' => 'nullable|exists:users,id',
            'infrastructureCostMonthly' => 'nullable|numeric|min:0',
            'infrastructureCostYearly' => 'nullable|numeric|min:0',
            'infrastructureCapacity' => 'nullable|string|max:255',
            'infrastructureUtilizationPercent' => 'nullable|integer|min:0|max:100',
            'infrastructureProvider' => 'nullable|string|max:255',
            'infrastructureLocation' => 'nullable|string|max:255',
            'infrastructureRoadmapDate' => 'nullable|date',
            'infrastructureExpiresAt' => 'nullable|date|required_if:infrastructureClass,license',
            'infrastructureRenewalReminderDays' => 'nullable|integer|min:1|max:365',
            'infrastructureIsCritical' => 'boolean',
            'infrastructureTaxRate' => 'nullable|numeric|min:0|max:100',
            'trackingMode' => 'required|in:individual,group',
            'quantityTotal' => 'required_if:trackingMode,group|integer|min:1',
            'quantityInUse' => 'nullable|integer|min:0',
            'quantityReserved' => 'nullable|integer|min:0',
        ]);

        $quantityTotal = $validated['trackingMode'] === 'group'
            ? max(1, (int) ($validated['quantityTotal'] ?? 1))
            : 1;

        $quantityInUse = $validated['trackingMode'] === 'group'
            ? max(0, (int) ($validated['quantityInUse'] ?? 0))
            : 0;

        $quantityReserved = $validated['trackingMode'] === 'group'
            ? max(0, (int) ($validated['quantityReserved'] ?? 0))
            : 0;

        if ($quantityInUse + $quantityReserved > $quantityTotal) {
            $this->addError('quantityInUse', 'Las unidades en uso y reservadas no pueden superar el total.');
            return;
        }

        $data = [
            'name' => $validated['infrastructureName'],
            'description' => $validated['infrastructureDescription'] ?? null,
            'type' => $validated['infrastructureType'],
            'category' => $validated['infrastructureCategory'],
            'infrastructure_class' => $validated['infrastructureClass'],
            'acquisition_status' => $validated['infrastructureAcquisitionStatus'],
            'status' => $validated['infrastructureStatus'],
            'owner_id' => $validated['infrastructureOwnerId'] ?: null,
            'cost_monthly' => $validated['infrastructureCostMonthly'] ?: null,
            'cost_yearly' => $validated['infrastructureCostYearly'] ?: null,
            'tax_rate' => $validated['infrastructureTaxRate'] ?? $this->defaultTaxRate,
            'capacity' => $validated['infrastructureCapacity'] ?? null,
            'utilization_percent' => $validated['infrastructureUtilizationPercent'] ?: null,
            'provider' => $validated['infrastructureProvider'] ?? null,
            'location' => $validated['infrastructureLocation'] ?? null,
            'roadmap_date' => $validated['infrastructureRoadmapDate'] ? \Carbon\Carbon::parse($validated['infrastructureRoadmapDate']) : null,
            'expires_at' => $validated['infrastructureExpiresAt'] ? \Carbon\Carbon::parse($validated['infrastructureExpiresAt']) : null,
            'renewal_reminder_days' => $validated['infrastructureRenewalReminderDays'] ?? 30,
            'is_critical' => $validated['infrastructureIsCritical'] ?? false,
            'tracking_mode' => $validated['trackingMode'],
            'quantity' => $quantityTotal,
            'quantity_in_use' => $quantityInUse,
            'quantity_reserved' => $quantityReserved,
            'area_id' => $this->plan->area_id,
            'plan_id' => $this->plan->id,
        ];

        try {
            if ($this->infrastructureId) {
                Infrastructure::where('id', $this->infrastructureId)->update($data);
                session()->flash('success', 'Infraestructura actualizada correctamente');
            } else {
                Infrastructure::create($data);
                session()->flash('success', 'Infraestructura creada correctamente');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar: ' . $e->getMessage());
            return;
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
        
        // Usar primer valor disponible de las opciones dinámicas, o valores por defecto
        $classes = array_keys($this->attributeOptions['class'] ?? []);
        $this->infrastructureClass = !empty($classes) ? $classes[0] : 'hardware';
        
        $acquisitionStatuses = array_keys($this->attributeOptions['acquisition_status'] ?? []);
        $this->infrastructureAcquisitionStatus = !empty($acquisitionStatuses) ? $acquisitionStatuses[0] : 'purchased';
        
        $statuses = array_keys($this->attributeOptions['operational_status'] ?? []);
        $this->infrastructureStatus = !empty($statuses) ? $statuses[0] : 'active';
        $this->infrastructureOwnerId = null;
        $this->infrastructureCostMonthly = null;
        $this->infrastructureCostYearly = null;
        $this->infrastructureTaxRate = $this->defaultTaxRate;
        $this->infrastructureCapacity = '';
        $this->infrastructureUtilizationPercent = null;
        $this->infrastructureProvider = '';
        $this->infrastructureLocation = '';
        $this->infrastructureRoadmapDate = null;
        $this->infrastructureExpiresAt = null;
        $this->infrastructureRenewalReminderDays = 30;
        $this->infrastructureIsCritical = false;
        $this->trackingMode = 'individual';
        $this->quantityTotal = 1;
        $this->quantityInUse = 0;
        $this->quantityReserved = 0;
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

        if ($this->filterInfrastructureClass) {
            $infrastructuresQuery->where('infrastructure_class', $this->filterInfrastructureClass);
        }

        if ($this->acquisitionStatus) {
            $infrastructuresQuery->where('acquisition_status', $this->acquisitionStatus);
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
                })
                ->orWhere(function($q2) {
                    // Licencias caducadas o próximas a caducar
                    $q2->where('infrastructure_class', 'license')
                       ->whereNotNull('expires_at')
                       ->where(function($q3) {
                           $reminderDays = 30; // Default
                           $q3->where('expires_at', '<', now()) // Caducadas
                              ->orWhere('expires_at', '<=', now()->addDays($reminderDays)); // Próximas a caducar
                       });
                });
            });
        }

        if ($this->showExpiringLicenses) {
            $infrastructuresQuery->where('infrastructure_class', 'license')
                ->whereNotNull('expires_at')
                ->where(function($q) {
                    $reminderDays = 30;
                    $q->where('expires_at', '<', now()) // Caducadas
                      ->orWhere('expires_at', '<=', now()->addDays($reminderDays)); // Próximas a caducar
                });
        }

        // Optimizar query con eager loading y select específico
        $infrastructures = $infrastructuresQuery
            ->select([
                'id',
                'name',
                'description',
                'type',
                'category',
                'infrastructure_class',
                'acquisition_status',
                'status',
                'owner_id',
                'cost_monthly',
                'cost_yearly',
                'tax_rate',
                'capacity',
                'utilization_percent',
                'provider',
                'location',
                'roadmap_date',
                'expires_at',
                'renewal_reminder_days',
                'is_critical',
                'area_id',
                'plan_id',
                'tracking_mode',
                'quantity',
                'quantity_in_use',
                'quantity_reserved',
                'created_at',
                'updated_at',
            ])
            ->with(['owner:id,name', 'area:id,name'])
            ->orderBy('infrastructure_class')
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        // Filtrar alertas si está activo
        if ($this->showAlertsOnly) {
            $infrastructures = $infrastructures->filter(function($infra) {
                return $infra->is_critical && !$infra->owner_id || // Crítico sin propietario
                       ($infra->utilization_percent !== null && $infra->utilization_percent > 80) || // Alta utilización
                       ($infra->status === 'active' && !$infra->owner_id) || // Activo sin propietario
                       ($infra->infrastructure_class === 'license' && $infra->isExpired()) || // Licencia caducada
                       ($infra->infrastructure_class === 'license' && $infra->isExpiringSoon()); // Licencia próxima a caducar
            });
        }

        // Calcular estadísticas - optimizado con select específico
        $allInfrastructures = Infrastructure::where('area_id', $this->plan->area_id)
            ->where(function($q) {
                $q->where('plan_id', $this->plan->id)
                  ->orWhereNull('plan_id');
            })
            ->select([
                'id',
                'name',
                'type',
                'category',
                'provider',
                'infrastructure_class',
                'status',
                'is_critical',
                'owner_id',
                'cost_monthly',
                'cost_yearly',
                'utilization_percent',
                'acquisition_status',
                'expires_at',
                'renewal_reminder_days',
                'tracking_mode',
                'quantity',
                'quantity_in_use',
                'quantity_reserved',
            ])
            ->get();

        // Separar por clase
        $licenses = $allInfrastructures->where('infrastructure_class', 'license');
        $hardware = $allInfrastructures->where('infrastructure_class', 'hardware');
        
        // Licencias expirando o caducadas
        $expiringLicenses = $licenses->filter(function($license) {
            return $license->isExpired() || $license->isExpiringSoon();
        });

        $stats = [
            'total' => $this->sumUnits($allInfrastructures),
            'total_records' => $allInfrastructures->count(),
            'licenses' => $this->sumUnits($licenses),
            'hardware' => $this->sumUnits($hardware),
            'active' => $this->sumUnits($allInfrastructures->where('status', 'active')),
            'planned' => $this->sumUnits($allInfrastructures->where('status', 'planned')),
            'critical' => $this->sumUnits($allInfrastructures->where('is_critical', true)),
            'total_monthly_cost' => $allInfrastructures->sum('cost_monthly') ?? 0,
            'total_yearly_cost' => $allInfrastructures->sum(function($i) {
                if ($i->cost_yearly) return $i->cost_yearly;
                if ($i->cost_monthly) return $i->cost_monthly * 12;
                return 0;
            }),
            'avg_utilization' => $allInfrastructures->whereNotNull('utilization_percent')->avg('utilization_percent') ?? 0,
            'critical_without_owner' => $this->sumUnits($allInfrastructures, fn($item) => $item->is_critical && !$item->owner_id),
            'high_utilization' => $this->sumUnits($allInfrastructures->where('utilization_percent', '>', 80)),
            'without_owner' => $this->sumUnits($allInfrastructures->whereNull('owner_id')),
            'expiring_licenses' => $this->sumUnits($expiringLicenses),
            'expired_licenses' => $this->sumUnits($licenses->filter(fn($l) => $l->isExpired())),
            'purchased' => $this->sumUnits($allInfrastructures->where('acquisition_status', 'purchased')),
            'to_purchase' => $this->sumUnits($allInfrastructures->where('acquisition_status', 'to_purchase')),
            'planned_acquisition' => $this->sumUnits($allInfrastructures->where('acquisition_status', 'planned')),
            'available_units' => $allInfrastructures->sum(function ($item) {
                if ($item->tracking_mode === 'group') {
                    return $item->quantity_available;
                }
                return $item->owner_id ? 0 : 1;
            }),
            'in_use_units' => $allInfrastructures->sum(function ($item) {
                if ($item->tracking_mode === 'group') {
                    return min($item->quantity_total, $item->quantity_in_use ?? 0);
                }
                return $item->owner_id ? 1 : 0;
            }),
            'reserved_units' => $allInfrastructures->sum(function ($item) {
                if ($item->tracking_mode === 'group') {
                    return min($item->quantity_total, $item->quantity_reserved ?? 0);
                }
                return 0;
            }),
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

        // Agrupar por clase (licencias vs hardware)
        $byClass = $infrastructures->groupBy('infrastructure_class');

        // Agrupar por categoría
        $byCategory = $infrastructures->groupBy('category');

        // Agrupar por tipo
        $byType = $infrastructures->groupBy('type');

        // Agrupar por estado de adquisición
        $byAcquisitionStatus = $infrastructures->groupBy('acquisition_status');

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
            'byClass' => $byClass,
            'byCategory' => $byCategory,
            'byType' => $byType,
            'byAcquisitionStatus' => $byAcquisitionStatus,
            'roadmap' => $roadmap,
            'types' => $types,
            'categories' => $categories,
            'providers' => $providers,
            'costsByCategory' => $costsByCategory,
            'costsByProvider' => $costsByProvider,
            'areaUsers' => $areaUsers,
            'licenses' => $licenses,
            'hardware' => $hardware,
            'expiringLicenses' => $expiringLicenses,
            'attributeOptions' => $this->attributeOptions,
        ]);
    }

    protected function sumUnits($collection, callable $filter = null): int
    {
        if ($filter) {
            $collection = $collection->filter($filter);
        }

        return $collection->sum(function ($item) {
            return $item->effective_units ?? ($item->tracking_mode === 'group'
                ? max(1, $item->quantity ?? 1)
                : 1);
        });
    }
}
