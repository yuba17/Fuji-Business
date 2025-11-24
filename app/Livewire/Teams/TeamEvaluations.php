<?php

namespace App\Livewire\Teams;

use App\Models\User;
use App\Models\UserEvaluation;
use App\Models\Competency;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class TeamEvaluations extends Component
{
    use WithPagination;

    // Filtros
    public $search = '';
    public $userId = '';
    public $type = '';
    public $status = '';
    public $showOverdueOnly = false;
    public $showUpcomingOnly = false;
    
    // Modal
    public $showEvaluationModal = false;
    public $evaluationId = null;
    
    // Formulario
    public $evaluationUserId = null;
    public $evaluationType = 'quarterly';
    public $evaluationDate = null;
    public $evaluationOverallScore = null;
    public $evaluationStrengths = '';
    public $evaluationAreasForImprovement = '';
    public $evaluationAchievements = '';
    public $evaluationFeedback = '';
    public $evaluationGoalsAchieved = [];
    public $evaluationGoalsSet = [];
    public $evaluationCareerDevelopmentPlan = [];
    public $evaluationCompetencyScores = [];
    public $evaluationNextEvaluationDate = null;
    public $evaluationNotes = '';
    public $evaluationStatus = 'draft';

    protected $listeners = ['evaluationUpdated' => '$refresh'];

    public function mount()
    {
        $this->evaluationDate = now()->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openEvaluationModal($evaluationId = null, $userId = null)
    {
        $this->evaluationId = $evaluationId;
        $this->evaluationUserId = $userId;
        
        if ($evaluationId) {
            $evaluation = UserEvaluation::findOrFail($evaluationId);
            $this->evaluationUserId = $evaluation->user_id;
            $this->evaluationType = $evaluation->type;
            $this->evaluationDate = $evaluation->evaluation_date->format('Y-m-d');
            $this->evaluationOverallScore = $evaluation->overall_score;
            $this->evaluationStrengths = $evaluation->strengths ?? '';
            $this->evaluationAreasForImprovement = $evaluation->areas_for_improvement ?? '';
            $this->evaluationAchievements = $evaluation->achievements ?? '';
            $this->evaluationFeedback = $evaluation->feedback ?? '';
            $this->evaluationGoalsAchieved = $evaluation->goals_achieved ?? [];
            $this->evaluationGoalsSet = $evaluation->goals_set ?? [];
            $this->evaluationCareerDevelopmentPlan = $evaluation->career_development_plan ?? [];
            $this->evaluationCompetencyScores = $evaluation->competency_scores ?? [];
            $this->evaluationNextEvaluationDate = $evaluation->next_evaluation_date?->format('Y-m-d');
            $this->evaluationNotes = $evaluation->notes ?? '';
            $this->evaluationStatus = $evaluation->status;
        } else {
            $this->resetEvaluationForm();
            if ($userId) {
                $this->evaluationUserId = $userId;
            }
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
            'evaluationType' => 'required|in:quarterly,biannual,annual,probationary,promotion,custom',
            'evaluationDate' => 'required|date',
            'evaluationOverallScore' => 'nullable|integer|min:1|max:5',
            'evaluationStrengths' => 'nullable|string',
            'evaluationAreasForImprovement' => 'nullable|string',
            'evaluationAchievements' => 'nullable|string',
            'evaluationFeedback' => 'nullable|string',
            'evaluationGoalsAchieved' => 'nullable|array',
            'evaluationGoalsSet' => 'nullable|array',
            'evaluationCareerDevelopmentPlan' => 'nullable|array',
            'evaluationCompetencyScores' => 'nullable|array',
            'evaluationNextEvaluationDate' => 'nullable|date|after:evaluationDate',
            'evaluationNotes' => 'nullable|string',
            'evaluationStatus' => 'required|in:draft,in_progress,completed,approved,rejected',
        ]);

        $data = [
            'user_id' => $validated['evaluationUserId'],
            'evaluator_id' => Auth::id(),
            'evaluation_date' => $validated['evaluationDate'],
            'type' => $validated['evaluationType'],
            'status' => $validated['evaluationStatus'],
            'overall_score' => $validated['evaluationOverallScore'],
            'strengths' => $validated['evaluationStrengths'],
            'areas_for_improvement' => $validated['evaluationAreasForImprovement'],
            'achievements' => $validated['evaluationAchievements'],
            'feedback' => $validated['evaluationFeedback'],
            'goals_achieved' => $validated['evaluationGoalsAchieved'],
            'goals_set' => $validated['evaluationGoalsSet'],
            'career_development_plan' => $validated['evaluationCareerDevelopmentPlan'],
            'competency_scores' => $validated['evaluationCompetencyScores'],
            'next_evaluation_date' => $validated['evaluationNextEvaluationDate'],
            'notes' => $validated['evaluationNotes'],
        ];

        if ($this->evaluationId) {
            UserEvaluation::findOrFail($this->evaluationId)->update($data);
            session()->flash('success', 'Evaluación actualizada correctamente');
        } else {
            UserEvaluation::create($data);
            
            // Actualizar last_evaluation_at del usuario si la evaluación está completada
            if ($validated['evaluationStatus'] === 'completed' || $validated['evaluationStatus'] === 'approved') {
                User::findOrFail($validated['evaluationUserId'])->update([
                    'last_evaluation_at' => $validated['evaluationDate']
                ]);
            }
            
            session()->flash('success', 'Evaluación creada correctamente');
        }

        $this->closeEvaluationModal();
        $this->dispatch('evaluationUpdated');
    }

    public function resetEvaluationForm()
    {
        $this->evaluationId = null;
        $this->evaluationUserId = null;
        $this->evaluationType = 'quarterly';
        $this->evaluationDate = now()->format('Y-m-d');
        $this->evaluationOverallScore = null;
        $this->evaluationStrengths = '';
        $this->evaluationAreasForImprovement = '';
        $this->evaluationAchievements = '';
        $this->evaluationFeedback = '';
        $this->evaluationGoalsAchieved = [];
        $this->evaluationGoalsSet = [];
        $this->evaluationCareerDevelopmentPlan = [];
        $this->evaluationCompetencyScores = [];
        $this->evaluationNextEvaluationDate = null;
        $this->evaluationNotes = '';
        $this->evaluationStatus = 'draft';
    }

    public function render()
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();
        
        // Obtener usuarios del equipo
        $teamUsers = $currentUser->directReports()
            ->with(['internalRole', 'area'])
            ->orderBy('name')
            ->get();

        // Obtener evaluaciones del equipo
        $evaluationsQuery = UserEvaluation::whereIn('user_id', $teamUsers->pluck('id'))
            ->with(['user', 'evaluator'])
            ->orderBy('evaluation_date', 'desc');

        if ($this->userId) {
            $evaluationsQuery->where('user_id', $this->userId);
        }

        if ($this->type) {
            $evaluationsQuery->where('type', $this->type);
        }

        if ($this->status) {
            $evaluationsQuery->where('status', $this->status);
        }

        if ($this->showOverdueOnly) {
            $evaluationsQuery->whereNotNull('next_evaluation_date')
                ->where('next_evaluation_date', '<', now());
        }

        if ($this->showUpcomingOnly) {
            $evaluationsQuery->whereNotNull('next_evaluation_date')
                ->where('next_evaluation_date', '>', now())
                ->where('next_evaluation_date', '<=', now()->addDays(30));
        }

        if ($this->search) {
            $evaluationsQuery->where(function($q) {
                $q->whereHas('user', function($userQuery) {
                    $userQuery->where('name', 'like', '%' . $this->search . '%')
                              ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orWhere('strengths', 'like', '%' . $this->search . '%')
                ->orWhere('feedback', 'like', '%' . $this->search . '%');
            });
        }

        $evaluations = $evaluationsQuery->paginate(15);

        // Estadísticas
        $allTeamEvaluations = UserEvaluation::whereIn('user_id', $teamUsers->pluck('id'))->get();
        
        $stats = [
            'total' => $allTeamEvaluations->count(),
            'completed' => $allTeamEvaluations->where('status', 'completed')->count(),
            'approved' => $allTeamEvaluations->where('status', 'approved')->count(),
            'overdue' => $allTeamEvaluations->filter(function($eval) {
                return $eval->isOverdue();
            })->count(),
            'upcoming' => $allTeamEvaluations->filter(function($eval) {
                return $eval->isUpcoming();
            })->count(),
            'avg_score' => round($allTeamEvaluations->whereNotNull('overall_score')->avg('overall_score') ?? 0, 1),
        ];

        // Obtener competencias para el formulario
        $competencies = Competency::where('is_active', true)
            ->where(function($q) use ($currentUser) {
                $q->where('area_id', $currentUser->area_id)
                  ->orWhereNull('area_id');
            })
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        return view('livewire.teams.team-evaluations', [
            'evaluations' => $evaluations,
            'teamUsers' => $teamUsers,
            'stats' => $stats,
            'competencies' => $competencies,
            'types' => ['quarterly' => 'Trimestral', 'biannual' => 'Semestral', 'annual' => 'Anual', 'probationary' => 'Prueba', 'promotion' => 'Promoción', 'custom' => 'Personalizada'],
            'statuses' => ['draft' => 'Borrador', 'in_progress' => 'En Progreso', 'completed' => 'Completada', 'approved' => 'Aprobada', 'rejected' => 'Rechazada'],
        ]);
    }
}
