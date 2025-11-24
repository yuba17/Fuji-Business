<?php

namespace App\Livewire\Profile;

use App\Models\User;
use App\Models\UserEvaluation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class UserEvaluations extends Component
{
    use WithPagination;

    public ?int $userId = null;
    public User $user;
    
    // Filtros
    public $type = '';
    public $status = '';
    public $search = '';
    
    // Modal
    public $showEvaluationModal = false;
    public $evaluationId = null;
    
    protected $listeners = ['evaluationUpdated' => '$refresh'];

    public function mount(?int $userId = null)
    {
        $this->userId = $userId ?? Auth::id();
        $this->user = User::findOrFail($this->userId);
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

    public function render()
    {
        $evaluationsQuery = $this->user->evaluations()
            ->with(['evaluator'])
            ->orderBy('evaluation_date', 'desc');

        if ($this->type) {
            $evaluationsQuery->where('type', $this->type);
        }

        if ($this->status) {
            $evaluationsQuery->where('status', $this->status);
        }

        if ($this->search) {
            $evaluationsQuery->where(function($q) {
                $q->where('strengths', 'like', '%' . $this->search . '%')
                  ->orWhere('feedback', 'like', '%' . $this->search . '%')
                  ->orWhere('notes', 'like', '%' . $this->search . '%');
            });
        }

        $evaluations = $evaluationsQuery->paginate(10);

        // Estadísticas
        $stats = [
            'total' => $this->user->evaluations()->count(),
            'completed' => $this->user->evaluations()->where('status', 'completed')->count(),
            'approved' => $this->user->evaluations()->where('status', 'approved')->count(),
            'overdue' => $this->user->evaluations()
                ->whereNotNull('next_evaluation_date')
                ->where('next_evaluation_date', '<', now())
                ->count(),
            'upcoming' => $this->user->evaluations()
                ->whereNotNull('next_evaluation_date')
                ->where('next_evaluation_date', '>', now())
                ->where('next_evaluation_date', '<=', now()->addDays(30))
                ->count(),
            'avg_score' => round($this->user->evaluations()
                ->whereNotNull('overall_score')
                ->avg('overall_score') ?? 0, 1),
        ];

        // Próxima evaluación
        $nextEvaluation = $this->user->evaluations()
            ->whereNotNull('next_evaluation_date')
            ->where('next_evaluation_date', '>', now())
            ->orderBy('next_evaluation_date', 'asc')
            ->first();

        return view('livewire.profile.user-evaluations', [
            'evaluations' => $evaluations,
            'stats' => $stats,
            'nextEvaluation' => $nextEvaluation,
            'types' => ['quarterly' => 'Trimestral', 'biannual' => 'Semestral', 'annual' => 'Anual', 'probationary' => 'Prueba', 'promotion' => 'Promoción', 'custom' => 'Personalizada'],
            'statuses' => ['draft' => 'Borrador', 'in_progress' => 'En Progreso', 'completed' => 'Completada', 'approved' => 'Aprobada', 'rejected' => 'Rechazada'],
        ]);
    }
}
