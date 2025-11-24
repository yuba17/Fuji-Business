<?php

namespace App\Livewire\Plans;

use App\Models\Plan;
use App\Models\Tooling;
use App\Models\ToolingAttribute;
use App\Models\ToolingMilestone;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class PlanDesarrolloIDTooling extends Component
{
    use WithPagination;

    public Plan $plan;
    
    // Vista actual
    public $viewMode = 'dashboard'; // dashboard, catalog, roadmap
    
    // Filtros para catálogo
    public $search = '';
    public $type = '';
    public $status = '';
    public $criticality = '';
    public $ownerId = '';
    
    // Filtros para roadmap
    public $roadmapYear = null;
    public $roadmapQuarter = '';
    public $roadmapStatus = '';
    public $roadmapPriority = '';
    
    // Modal de tooling
    public $showToolingModal = false;
    public $showToolingDetailsModal = false;
    public $selectedToolingForDetails = null;
    public $toolingId = null;
    public $toolingName = '';
    public $toolingType = 'otro';
    public $toolingDescription = '';
    public $toolingStatus = 'idea';
    public $toolingCriticality = 'media';
    public $toolingStartedAt = null;
    public $toolingLastUpdatedAt = null;
    public $toolingOwnerId = null;
    public $toolingContactReference = '';
    public $toolingBenefits = '';
    public $useScenarios = [];
    public $impactMetrics = [];
    public $selectedTeamMembers = [];
    
    // Modal de milestone
    public $showMilestoneModal = false;
    public $showMilestoneDetailsModal = false;
    public $selectedMilestoneForDetails = null;
    public $milestoneId = null;
    public $milestoneToolingId = null;
    public $milestoneTitle = '';
    public $milestoneDescription = '';
    public $milestoneType = 'otro';
    public $milestoneTargetQuarter = '';
    public $milestoneTargetYear = null;
    public $milestonePriority = 'media';
    public $milestoneStatus = 'planificado';
    public $milestoneAssignedToId = null;
    public $milestoneCompletedAt = null;
    public $milestoneNotes = '';
    public $milestoneChecklist = [];
    public $newChecklistItem = '';
    
    // Tooling seleccionado para ver milestones
    public $selectedToolingId = null;

    protected $listeners = ['toolingUpdated' => '$refresh', 'milestoneUpdated' => '$refresh'];

    public function mount(Plan $plan)
    {
        $this->plan = $plan;
        $this->roadmapYear = now()->year;
        $this->viewMode = 'dashboard';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingType()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingCriticality()
    {
        $this->resetPage();
    }

    public function updatingOwnerId()
    {
        $this->resetPage();
    }

    // ========== TOOLING METHODS ==========

    public function openToolingModal($toolingId = null)
    {
        // Cerrar modal de detalles si está abierto
        if ($this->showToolingDetailsModal) {
            $this->closeToolingDetailsModal();
        }
        
        $this->toolingId = $toolingId;
        
        if ($toolingId) {
            $tooling = Tooling::find($toolingId);
            if (!$tooling) {
                session()->flash('error', 'Herramienta no encontrada');
                return;
            }
            $this->toolingName = $tooling->name;
            $this->toolingType = $tooling->type;
            $this->toolingDescription = $tooling->description ?? '';
            $this->toolingStatus = $tooling->status;
            $this->toolingCriticality = $tooling->criticality;
            $this->toolingStartedAt = $tooling->started_at?->format('Y-m-d');
            $this->toolingLastUpdatedAt = $tooling->last_updated_at?->format('Y-m-d');
            $this->toolingOwnerId = $tooling->owner_id;
            $this->toolingContactReference = $tooling->contact_reference ?? '';
            $this->toolingBenefits = $tooling->benefits ?? '';
            $this->useScenarios = $tooling->use_scenarios ?? [];
            $this->impactMetrics = $tooling->impact_metrics ?? [];
            $this->selectedTeamMembers = $tooling->teamMembers->pluck('id')->toArray();
        } else {
            $this->resetToolingForm();
        }
        
        $this->showToolingModal = true;
    }

    public function closeToolingModal()
    {
        $this->showToolingModal = false;
        $this->resetToolingForm();
    }

    public function saveTooling()
    {
        $validated = $this->validate([
            'toolingName' => 'required|string|max:255',
            'toolingType' => ['required', 'in:' . implode(',', array_keys($this->toolingTypeOptions))],
            'toolingDescription' => 'nullable|string',
            'toolingStatus' => ['required', 'in:' . implode(',', array_keys($this->toolingStatusOptions))],
            'toolingCriticality' => ['required', 'in:' . implode(',', array_keys($this->toolingCriticalityOptions))],
            'toolingStartedAt' => 'nullable|date',
            'toolingLastUpdatedAt' => 'nullable|date',
            'toolingOwnerId' => 'nullable|exists:users,id',
            'toolingContactReference' => 'nullable|string|max:255',
            'toolingBenefits' => 'nullable|string',
            'useScenarios' => 'nullable|array',
            'impactMetrics' => 'nullable|array',
            'selectedTeamMembers' => 'nullable|array',
        ]);

        $data = [
            'name' => $validated['toolingName'],
            'type' => $validated['toolingType'],
            'description' => $validated['toolingDescription'] ?? null,
            'status' => $validated['toolingStatus'],
            'criticality' => $validated['toolingCriticality'],
            'started_at' => $validated['toolingStartedAt'] ? \Carbon\Carbon::parse($validated['toolingStartedAt']) : null,
            'last_updated_at' => $validated['toolingLastUpdatedAt'] ? \Carbon\Carbon::parse($validated['toolingLastUpdatedAt']) : null,
            'owner_id' => $validated['toolingOwnerId'] ?: null,
            'contact_reference' => $validated['toolingContactReference'] ?? null,
            'benefits' => $validated['toolingBenefits'] ?? null,
            'use_scenarios' => $validated['useScenarios'] ?? [],
            'impact_metrics' => $validated['impactMetrics'] ?? [],
            'plan_id' => $this->plan->id,
            'area_id' => $this->plan->area_id,
        ];

        try {
            if ($this->toolingId) {
                $tooling = Tooling::find($this->toolingId);
                $tooling->update($data);
                $tooling->teamMembers()->sync($validated['selectedTeamMembers'] ?? []);
                session()->flash('success', 'Herramienta actualizada correctamente');
            } else {
                $tooling = Tooling::create($data);
                $tooling->teamMembers()->sync($validated['selectedTeamMembers'] ?? []);
                session()->flash('success', 'Herramienta creada correctamente');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar: ' . $e->getMessage());
            return;
        }

        $this->closeToolingModal();
        $this->dispatch('toolingUpdated');
        
        // Si había un modal de detalles abierto, actualizarlo
        if ($this->showToolingDetailsModal && $this->selectedToolingForDetails) {
            $this->selectedToolingForDetails = Tooling::with(['owner', 'teamMembers', 'milestones.assignedTo'])->find($this->selectedToolingForDetails->id);
        }
    }

    public function deleteTooling($toolingId)
    {
        try {
            $tooling = Tooling::find($toolingId);
            if (!$tooling) {
                session()->flash('error', 'Herramienta no encontrada');
                return;
            }
            $tooling->delete();
            session()->flash('success', 'Herramienta eliminada correctamente');
            
            // Cerrar modal de detalles si está abierto
            if ($this->showToolingDetailsModal) {
                $this->closeToolingDetailsModal();
            }
            
            $this->dispatch('toolingUpdated');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    public function openToolingDetailsModal($toolingId)
    {
        $this->selectedToolingForDetails = Tooling::with(['owner', 'teamMembers', 'milestones.assignedTo'])->find($toolingId);
        if (!$this->selectedToolingForDetails) {
            session()->flash('error', 'Herramienta no encontrada');
            return;
        }
        $this->showToolingDetailsModal = true;
    }

    public function closeToolingDetailsModal()
    {
        $this->showToolingDetailsModal = false;
        $this->selectedToolingForDetails = null;
    }

    public function resetToolingForm()
    {
        $this->toolingId = null;
        $this->toolingName = '';
        $this->toolingType = array_key_first($this->toolingTypeOptions) ?? '';
        $this->toolingDescription = '';
        $this->toolingStatus = array_key_first($this->toolingStatusOptions) ?? '';
        $this->toolingCriticality = array_key_first($this->toolingCriticalityOptions) ?? '';
        $this->toolingStartedAt = null;
        $this->toolingLastUpdatedAt = null;
        $this->toolingOwnerId = null;
        $this->toolingContactReference = '';
        $this->toolingBenefits = '';
        $this->useScenarios = [];
        $this->impactMetrics = [];
        $this->selectedTeamMembers = [];
    }

    // ========== MILESTONE METHODS ==========

    public function openMilestoneModal($milestoneId = null, $toolingId = null)
    {
        $this->milestoneId = $milestoneId;
        
        if ($milestoneId) {
            $milestone = ToolingMilestone::find($milestoneId);
            if (!$milestone) {
                session()->flash('error', 'Hito no encontrado');
                return;
            }
            $this->milestoneToolingId = $milestone->tooling_id;
            $this->milestoneTitle = $milestone->title;
            $this->milestoneDescription = $milestone->description ?? '';
            $this->milestoneType = $milestone->milestone_type;
            $this->milestoneTargetQuarter = $milestone->target_quarter ?? '';
            $this->milestoneTargetYear = $milestone->target_year;
            $this->milestonePriority = $milestone->priority;
            $this->milestoneStatus = $milestone->status;
            $this->milestoneAssignedToId = $milestone->assigned_to_id;
            $this->milestoneCompletedAt = $milestone->completed_at?->format('Y-m-d');
            $this->milestoneNotes = $milestone->notes ?? '';
            $this->milestoneChecklist = $milestone->checklist ?? [];
        } else {
            $this->resetMilestoneForm();
            // Si se pasa un toolingId, usarlo; si no, dejar null para que el usuario seleccione
            if ($toolingId) {
                $this->milestoneToolingId = $toolingId;
            }
        }
        
        // Cerrar el modal de detalles si está abierto
        if ($this->showMilestoneDetailsModal) {
            $this->closeMilestoneDetailsModal();
        }
        
        $this->showMilestoneModal = true;
    }

    public function closeMilestoneModal()
    {
        $this->showMilestoneModal = false;
        $this->resetMilestoneForm();
    }

    public function saveMilestone()
    {
        $validated = $this->validate([
            'milestoneToolingId' => 'required|exists:toolings,id',
            'milestoneTitle' => 'required|string|max:255',
            'milestoneDescription' => 'nullable|string',
            'milestoneType' => ['required', 'in:' . implode(',', array_keys($this->milestoneTypeOptions))],
            'milestoneTargetQuarter' => 'nullable|in:Q1,Q2,Q3,Q4',
            'milestoneTargetYear' => 'nullable|integer|min:2020|max:2100',
            'milestonePriority' => ['required', 'in:' . implode(',', array_keys($this->milestonePriorityOptions))],
            'milestoneStatus' => ['required', 'in:' . implode(',', array_keys($this->milestoneStatusOptions))],
            'milestoneAssignedToId' => 'nullable|exists:users,id',
            'milestoneCompletedAt' => 'nullable|date',
            'milestoneNotes' => 'nullable|string',
            'milestoneChecklist' => 'nullable|array',
        ]);

        $data = [
            'tooling_id' => $validated['milestoneToolingId'],
            'title' => $validated['milestoneTitle'],
            'description' => $validated['milestoneDescription'] ?? null,
            'milestone_type' => $validated['milestoneType'],
            'target_quarter' => $validated['milestoneTargetQuarter'] ?? null,
            'target_year' => $validated['milestoneTargetYear'] ?? null,
            'priority' => $validated['milestonePriority'],
            'status' => $validated['milestoneStatus'],
            'assigned_to_id' => $validated['milestoneAssignedToId'] ?: null,
            'completed_at' => $validated['milestoneCompletedAt'] ? \Carbon\Carbon::parse($validated['milestoneCompletedAt']) : null,
            'notes' => $validated['milestoneNotes'] ?? null,
            'checklist' => $this->milestoneChecklist ?? [],
        ];

        try {
            if ($this->milestoneId) {
                ToolingMilestone::where('id', $this->milestoneId)->update($data);
                session()->flash('success', 'Hito actualizado correctamente');
            } else {
                ToolingMilestone::create($data);
                session()->flash('success', 'Hito creado correctamente');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar: ' . $e->getMessage());
            return;
        }

        $this->closeMilestoneModal();
        $this->dispatch('milestoneUpdated');
    }

    public function deleteMilestone($milestoneId)
    {
        try {
            $milestone = ToolingMilestone::find($milestoneId);
            if (!$milestone) {
                session()->flash('error', 'Hito no encontrado');
                return;
            }
            $milestone->delete();
            session()->flash('success', 'Hito eliminado correctamente');
            $this->dispatch('milestoneUpdated');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    public function resetMilestoneForm()
    {
        $this->milestoneId = null;
        $this->milestoneToolingId = null;
        $this->milestoneTitle = '';
        $this->milestoneDescription = '';
        $this->milestoneType = array_key_first($this->milestoneTypeOptions) ?? '';
        $this->milestoneTargetQuarter = '';
        $this->milestoneTargetYear = $this->roadmapYear ?? now()->year;
        $this->milestonePriority = array_key_first($this->milestonePriorityOptions) ?? '';
        $this->milestoneStatus = array_key_first($this->milestoneStatusOptions) ?? '';
        $this->milestoneAssignedToId = null;
        $this->milestoneCompletedAt = null;
        $this->milestoneNotes = '';
        $this->milestoneChecklist = [];
        $this->newChecklistItem = '';
    }

    public function addChecklistItem()
    {
        $itemText = trim($this->newChecklistItem);
        if (!empty($itemText)) {
            $this->milestoneChecklist[] = [
                'id' => uniqid(),
                'text' => $itemText,
                'completed' => false,
            ];
            $this->newChecklistItem = '';
            $this->dispatch('checklist-item-added');
        }
    }

    public function openMilestoneDetailsModal($milestoneId)
    {
        $this->selectedMilestoneForDetails = ToolingMilestone::with(['tooling', 'assignedTo'])->find($milestoneId);
        if (!$this->selectedMilestoneForDetails) {
            session()->flash('error', 'Hito no encontrado');
            return;
        }
        $this->showMilestoneDetailsModal = true;
    }

    public function closeMilestoneDetailsModal()
    {
        $this->showMilestoneDetailsModal = false;
        $this->selectedMilestoneForDetails = null;
    }

    public function toggleMilestoneChecklistItem($milestoneId, $index)
    {
        $milestone = ToolingMilestone::find($milestoneId);
        if (!$milestone) {
            return;
        }

        $checklist = $milestone->checklist ?? [];
        if (isset($checklist[$index])) {
            $checklist[$index]['completed'] = !($checklist[$index]['completed'] ?? false);
            $milestone->update(['checklist' => $checklist]);
            
            // Refrescar el milestone seleccionado
            if ($this->selectedMilestoneForDetails && $this->selectedMilestoneForDetails->id === $milestoneId) {
                $this->selectedMilestoneForDetails = ToolingMilestone::with(['tooling', 'assignedTo'])->find($milestoneId);
            }
            
            $this->dispatch('milestoneUpdated');
        }
    }

    public function removeChecklistItem($index)
    {
        unset($this->milestoneChecklist[$index]);
        $this->milestoneChecklist = array_values($this->milestoneChecklist);
    }

    public function toggleChecklistItem($index)
    {
        if (isset($this->milestoneChecklist[$index])) {
            $this->milestoneChecklist[$index]['completed'] = !$this->milestoneChecklist[$index]['completed'];
        }
    }

    // ========== COMPUTED PROPERTIES ==========
    
    public function getToolingTypeOptionsProperty()
    {
        return ToolingAttribute::optionsFor('type');
    }
    
    public function getToolingStatusOptionsProperty()
    {
        return ToolingAttribute::optionsFor('status');
    }
    
    public function getToolingCriticalityOptionsProperty()
    {
        return ToolingAttribute::optionsFor('criticality');
    }
    
    public function getMilestoneTypeOptionsProperty()
    {
        return ToolingAttribute::optionsFor('milestone_type');
    }
    
    public function getMilestonePriorityOptionsProperty()
    {
        return ToolingAttribute::optionsFor('milestone_priority');
    }
    
    public function getMilestoneStatusOptionsProperty()
    {
        return ToolingAttribute::optionsFor('milestone_status');
    }

    // ========== QUERY METHODS ==========

    public function getToolingsQuery()
    {
        $query = Tooling::where('plan_id', $this->plan->id)
            ->with(['owner', 'teamMembers', 'milestones']);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->type) {
            $query->where('type', $this->type);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->criticality) {
            $query->where('criticality', $this->criticality);
        }

        if ($this->ownerId) {
            $query->where('owner_id', $this->ownerId);
        }

        return $query->orderBy('name');
    }

    public function getMilestonesQuery()
    {
        $query = ToolingMilestone::whereHas('tooling', function($q) {
                $q->where('plan_id', $this->plan->id);
            })
            ->with(['tooling', 'assignedTo']);

        if ($this->roadmapYear) {
            $query->where('target_year', $this->roadmapYear);
        }

        if ($this->roadmapQuarter) {
            $query->where('target_quarter', $this->roadmapQuarter);
        }

        if ($this->roadmapStatus) {
            $query->where('status', $this->roadmapStatus);
        }

        if ($this->roadmapPriority) {
            $query->where('priority', $this->roadmapPriority);
        }

        return $query->orderBy('target_year')->orderBy('target_quarter')->orderBy('priority', 'desc');
    }

    public function getUsersProperty()
    {
        return User::orderBy('name')->get();
    }

    public function getAllToolingsProperty()
    {
        return Tooling::where('plan_id', $this->plan->id)->orderBy('name')->get();
    }

    // ========== STATISTICS METHODS ==========

    public function getStatisticsProperty()
    {
        $allToolings = Tooling::where('plan_id', $this->plan->id)->get();
        $allMilestones = ToolingMilestone::whereHas('tooling', function($q) {
            $q->where('plan_id', $this->plan->id);
        })->get();

        return [
            'total_toolings' => $allToolings->count(),
            'by_status' => [
                'idea' => $allToolings->where('status', 'idea')->count(),
                'en_evaluacion' => $allToolings->where('status', 'en_evaluacion')->count(),
                'en_desarrollo' => $allToolings->where('status', 'en_desarrollo')->count(),
                'beta' => $allToolings->where('status', 'beta')->count(),
                'produccion' => $allToolings->where('status', 'produccion')->count(),
                'obsoleta' => $allToolings->where('status', 'obsoleta')->count(),
            ],
            'by_type' => [
                'ofensiva' => $allToolings->where('type', 'ofensiva')->count(),
                'automatizacion' => $allToolings->where('type', 'automatizacion')->count(),
                'laboratorio' => $allToolings->where('type', 'laboratorio')->count(),
                'reporting' => $allToolings->where('type', 'reporting')->count(),
                'soporte' => $allToolings->where('type', 'soporte')->count(),
                'otro' => $allToolings->where('type', 'otro')->count(),
            ],
            'by_criticality' => [
                'alta' => $allToolings->where('criticality', 'alta')->count(),
                'media' => $allToolings->where('criticality', 'media')->count(),
                'baja' => $allToolings->where('criticality', 'baja')->count(),
            ],
            'milestones' => [
                'total' => $allMilestones->count(),
                'planificado' => $allMilestones->where('status', 'planificado')->count(),
                'en_curso' => $allMilestones->where('status', 'en_curso')->count(),
                'completado' => $allMilestones->where('status', 'completado')->count(),
                'bloqueado' => $allMilestones->where('status', 'bloqueado')->count(),
            ],
        ];
    }

    public function render()
    {
        $toolings = $this->viewMode === 'catalog' 
            ? $this->getToolingsQuery()->paginate(12)
            : ($this->viewMode === 'dashboard' ? collect() : $this->getToolingsQuery()->get());

        $milestones = $this->viewMode === 'roadmap'
            ? $this->getMilestonesQuery()->get()
            : collect();

        // Agrupar milestones por trimestre para el roadmap
        $milestonesByQuarter = [];
        if ($this->viewMode === 'roadmap') {
            foreach ($milestones as $milestone) {
                $key = ($milestone->target_year ?? 'Sin fecha') . '-' . ($milestone->target_quarter ?? 'Sin trimestre');
                if (!isset($milestonesByQuarter[$key])) {
                    $milestonesByQuarter[$key] = [
                        'year' => $milestone->target_year,
                        'quarter' => $milestone->target_quarter,
                        'milestones' => []
                    ];
                }
                $milestonesByQuarter[$key]['milestones'][] = $milestone;
            }
            ksort($milestonesByQuarter);
        }

        return view('livewire.plans.plan-desarrollo-i-d-tooling', [
            'toolings' => $toolings,
            'milestones' => $milestones,
            'milestonesByQuarter' => $milestonesByQuarter,
            'users' => $this->users,
            'allToolings' => $this->allToolings,
            'statistics' => $this->statistics,
        ]);
    }
}
