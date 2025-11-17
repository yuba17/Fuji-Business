<?php

namespace App\Livewire\Plans;

use App\Models\Plan;
use App\Models\Competency;
use App\Models\User;
use App\Models\UserCompetency;
use App\Models\InternalRole;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class PlanDesarrolloCompetencias extends Component
{
    use WithPagination;

    public Plan $plan;
    
    // Filtros
    public $search = '';
    public $category = '';
    public $internalRoleId = '';
    public $showCriticalOnly = false;
    
    // Modales
    public $showCompetencyModal = false;
    public $showEvaluationModal = false;
    
    // Formulario de competencia
    public $competencyId = null;
    public $competencyName = '';
    public $competencyDescription = '';
    public $competencyCategory = '';
    public $competencyInternalRoleId = '';
    public $competencyIsCritical = false;
    
    // Formulario de evaluación
    public $evaluationUserId = null;
    public $evaluationCompetencyId = null;
    public $evaluationCurrentLevel = 1;
    public $evaluationTargetLevel = null;
    public $evaluationNotes = '';

    protected $listeners = ['competencyUpdated' => '$refresh', 'evaluationUpdated' => '$refresh'];

    public function mount(Plan $plan)
    {
        $this->plan = $plan;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingInternalRoleId()
    {
        $this->resetPage();
    }

    public function openCompetencyModal($competencyId = null)
    {
        $this->competencyId = $competencyId;
        
        if ($competencyId) {
            $competency = Competency::find($competencyId);
            $this->competencyName = $competency->name;
            $this->competencyDescription = $competency->description ?? '';
            $this->competencyCategory = $competency->category ?? '';
            $this->competencyInternalRoleId = $competency->internal_role_id ?? '';
            $this->competencyIsCritical = $competency->is_critical;
        } else {
            $this->resetCompetencyForm();
        }
        
        $this->showCompetencyModal = true;
    }

    public function closeCompetencyModal()
    {
        $this->showCompetencyModal = false;
        $this->resetCompetencyForm();
    }

    public function saveCompetency()
    {
        $validated = $this->validate([
            'competencyName' => 'required|string|max:255',
            'competencyDescription' => 'nullable|string',
            'competencyCategory' => 'nullable|string|max:255',
            'competencyInternalRoleId' => 'nullable|exists:internal_roles,id',
            'competencyIsCritical' => 'boolean',
        ]);

        $data = [
            'name' => $validated['competencyName'],
            'description' => $validated['competencyDescription'],
            'category' => $validated['competencyCategory'],
            'internal_role_id' => $validated['competencyInternalRoleId'] ?: null,
            'is_critical' => $validated['competencyIsCritical'],
            'area_id' => $this->plan->area_id,
            'level_type' => 'numeric',
        ];

        if ($this->competencyId) {
            Competency::find($this->competencyId)->update($data);
            session()->flash('success', 'Competencia actualizada correctamente');
        } else {
            Competency::create($data);
            session()->flash('success', 'Competencia creada correctamente');
        }

        $this->closeCompetencyModal();
        $this->dispatch('competencyUpdated');
    }

    public function deleteCompetency($competencyId)
    {
        Competency::find($competencyId)->delete();
        session()->flash('success', 'Competencia eliminada correctamente');
        $this->dispatch('competencyUpdated');
    }

    public function openEvaluationModal($userId, $competencyId = null)
    {
        $this->evaluationUserId = $userId;
        $this->evaluationCompetencyId = $competencyId;
        
        if ($competencyId) {
            $userCompetency = UserCompetency::where('user_id', $userId)
                ->where('competency_id', $competencyId)
                ->first();
            
            if ($userCompetency) {
                $this->evaluationCurrentLevel = $userCompetency->current_level;
                $this->evaluationTargetLevel = $userCompetency->target_level;
                $this->evaluationNotes = $userCompetency->notes ?? '';
            } else {
                $this->resetEvaluationForm();
            }
        } else {
            $this->resetEvaluationForm();
        }
        
        $this->showEvaluationModal = true;
    }

    public function closeEvaluationModal()
    {
        $this->showEvaluationModal = false;
        $this->resetEvaluationForm();
    }

    public function saveEvaluation()
    {
        $validated = $this->validate([
            'evaluationUserId' => 'required|exists:users,id',
            'evaluationCompetencyId' => 'required|exists:competencies,id',
            'evaluationCurrentLevel' => 'required|integer|min:1|max:5',
            'evaluationTargetLevel' => 'nullable|integer|min:1|max:5',
            'evaluationNotes' => 'nullable|string',
        ]);

        UserCompetency::updateOrCreate(
            [
                'user_id' => $validated['evaluationUserId'],
                'competency_id' => $validated['evaluationCompetencyId'],
            ],
            [
                'current_level' => $validated['evaluationCurrentLevel'],
                'target_level' => $validated['evaluationTargetLevel'],
                'notes' => $validated['evaluationNotes'],
                'last_assessed_at' => now(),
                'assessed_by' => Auth::id(),
            ]
        );

        session()->flash('success', 'Evaluación guardada correctamente');
        $this->closeEvaluationModal();
        $this->dispatch('evaluationUpdated');
    }

    public function resetCompetencyForm()
    {
        $this->competencyId = null;
        $this->competencyName = '';
        $this->competencyDescription = '';
        $this->competencyCategory = '';
        $this->competencyInternalRoleId = '';
        $this->competencyIsCritical = false;
    }

    public function resetEvaluationForm()
    {
        $this->evaluationUserId = null;
        $this->evaluationCompetencyId = null;
        $this->evaluationCurrentLevel = 1;
        $this->evaluationTargetLevel = null;
        $this->evaluationNotes = '';
    }

    public function render()
    {
        // Obtener usuarios del equipo del plan
        $teamUsers = collect();
        if ($this->plan->area) {
            $teamUsers = User::where('area_id', $this->plan->area_id)
                ->with(['internalRole', 'competencies'])
                ->orderBy('name')
                ->get();
        }

        // Obtener competencias del área
        $competenciesQuery = Competency::where('area_id', $this->plan->area_id)
            ->where('is_active', true)
            ->with(['internalRole', 'userCompetencies']);

        if ($this->search) {
            $competenciesQuery->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->category) {
            $competenciesQuery->where('category', $this->category);
        }

        if ($this->internalRoleId) {
            $competenciesQuery->where('internal_role_id', $this->internalRoleId);
        }

        if ($this->showCriticalOnly) {
            $competenciesQuery->where('is_critical', true);
        }

        $competencies = $competenciesQuery->orderBy('category')->orderBy('name')->get();

        // Calcular gap analysis
        $gapAnalysis = [];
        foreach ($competencies as $competency) {
            $gapAnalysis[$competency->id] = [
                'competency' => $competency,
                'users' => [],
                'total_gap' => 0,
                'avg_current' => 0,
                'avg_target' => 0,
            ];

            foreach ($teamUsers as $user) {
                $userCompetency = UserCompetency::where('user_id', $user->id)
                    ->where('competency_id', $competency->id)
                    ->first();

                $current = $userCompetency ? $userCompetency->current_level : 0;
                $target = $userCompetency && $userCompetency->target_level ? $userCompetency->target_level : 0;
                $gap = $target > 0 ? max(0, $target - $current) : 0;

                $gapAnalysis[$competency->id]['users'][$user->id] = [
                    'user' => $user,
                    'current' => $current,
                    'target' => $target,
                    'gap' => $gap,
                    'userCompetency' => $userCompetency,
                ];

                if ($current > 0) {
                    $gapAnalysis[$competency->id]['avg_current'] += $current;
                }
                if ($target > 0) {
                    $gapAnalysis[$competency->id]['avg_target'] += $target;
                }
                $gapAnalysis[$competency->id]['total_gap'] += $gap;
            }

            $usersWithData = collect($gapAnalysis[$competency->id]['users'])->filter(fn($u) => $u['current'] > 0 || $u['target'] > 0)->count();
            if ($usersWithData > 0) {
                $gapAnalysis[$competency->id]['avg_current'] = round($gapAnalysis[$competency->id]['avg_current'] / $usersWithData, 1);
                $gapAnalysis[$competency->id]['avg_target'] = round($gapAnalysis[$competency->id]['avg_target'] / $usersWithData, 1);
            }
        }

        // Categorías disponibles
        $categories = Competency::where('area_id', $this->plan->area_id)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values();

        // Roles internos disponibles
        $internalRoles = InternalRole::whereHas('competencies', function($q) {
                $q->where('area_id', $this->plan->area_id);
            })
            ->orderBy('name')
            ->get();

        return view('livewire.plans.plan-desarrollo-competencias', [
            'competencies' => $competencies,
            'teamUsers' => $teamUsers,
            'gapAnalysis' => $gapAnalysis,
            'categories' => $categories,
            'internalRoles' => $internalRoles,
        ]);
    }
}
