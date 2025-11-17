<?php

namespace App\Livewire\Plans;

use App\Models\Plan;
use App\Models\Certification;
use App\Models\User;
use App\Models\UserCertification;
use App\Models\CertificationBadge;
use App\Models\InternalRole;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PlanDesarrolloCertificaciones extends Component
{
    use WithPagination;

    public Plan $plan;
    
    // Filtros
    public $search = '';
    public $category = '';
    public $provider = '';
    public $level = '';
    public $status = '';
    public $showCriticalOnly = false;
    public $showExpiringSoon = false;
    
    // Vista
    public $viewMode = 'inventory'; // inventory, matrix, roadmap, leaderboard, badges
    
    // Usuario seleccionado para roadmap personalizado
    public $selectedUserId = null;
    
    // Modales
    public $showCertificationModal = false;
    public $showUserCertificationModal = false;
    public $showBadgeModal = false;
    
    // Formulario de certificación
    public $certificationId = null;
    public $certificationName = '';
    public $certificationCode = '';
    public $certificationDescription = '';
    public $certificationProvider = '';
    public $certificationCategory = '';
    public $certificationLevel = '';
    public $certificationValidityMonths = null;
    public $certificationCost = null;
    public $certificationDifficultyScore = null;
    public $certificationValueScore = null;
    public $certificationIsCritical = false;
    public $certificationIsInternal = false;
    public $certificationPointsReward = 0;
    
    // Formulario de certificación de usuario
    public $userCertificationId = null;
    public $userCertificationUserId = null;
    public $userCertificationCertificationId = null;
    public $userCertificationObtainedAt = null;
    public $userCertificationExpiresAt = null;
    public $userCertificationCertificateNumber = '';
    public $userCertificationStatus = 'active';
    public $userCertificationPlannedDate = null;
    public $userCertificationPriority = 0;
    public $userCertificationNotes = '';

    protected $listeners = ['certificationUpdated' => '$refresh'];

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

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function openCertificationModal($certificationId = null)
    {
        $this->certificationId = $certificationId;
        
        if ($certificationId) {
            $cert = Certification::find($certificationId);
            $this->certificationName = $cert->name;
            $this->certificationCode = $cert->code ?? '';
            $this->certificationDescription = $cert->description ?? '';
            $this->certificationProvider = $cert->provider;
            $this->certificationCategory = $cert->category ?? '';
            $this->certificationLevel = $cert->level ?? '';
            $this->certificationValidityMonths = $cert->validity_months;
            $this->certificationCost = $cert->cost;
            $this->certificationDifficultyScore = $cert->difficulty_score;
            $this->certificationValueScore = $cert->value_score;
            $this->certificationIsCritical = $cert->is_critical;
            $this->certificationIsInternal = $cert->is_internal;
            $this->certificationPointsReward = $cert->points_reward;
        } else {
            $this->resetCertificationForm();
        }
        
        $this->showCertificationModal = true;
    }

    public function closeCertificationModal()
    {
        $this->showCertificationModal = false;
        $this->resetCertificationForm();
    }

    public function saveCertification()
    {
        $validated = $this->validate([
            'certificationName' => 'required|string|max:255',
            'certificationCode' => 'nullable|string|max:255|unique:certifications,code,' . $this->certificationId,
            'certificationDescription' => 'nullable|string',
            'certificationProvider' => 'required|string|max:255',
            'certificationCategory' => 'nullable|string|max:255',
            'certificationLevel' => 'nullable|string|max:255',
            'certificationValidityMonths' => 'nullable|integer|min:0',
            'certificationCost' => 'nullable|numeric|min:0',
            'certificationDifficultyScore' => 'nullable|integer|min:1|max:10',
            'certificationValueScore' => 'nullable|integer|min:1|max:10',
            'certificationIsCritical' => 'boolean',
            'certificationIsInternal' => 'boolean',
            'certificationPointsReward' => 'integer|min:0',
        ]);

        $data = [
            'name' => $validated['certificationName'],
            'code' => $validated['certificationCode'] ?: null,
            'description' => $validated['certificationDescription'],
            'provider' => $validated['certificationProvider'],
            'category' => $validated['certificationCategory'],
            'level' => $validated['certificationLevel'],
            'validity_months' => $validated['certificationValidityMonths'],
            'cost' => $validated['certificationCost'],
            'difficulty_score' => $validated['certificationDifficultyScore'],
            'value_score' => $validated['certificationValueScore'],
            'is_critical' => $validated['certificationIsCritical'],
            'is_internal' => $validated['certificationIsInternal'],
            'points_reward' => $validated['certificationPointsReward'],
        ];

        if ($this->certificationId) {
            Certification::find($this->certificationId)->update($data);
            session()->flash('success', 'Certificación actualizada correctamente');
        } else {
            Certification::create($data);
            session()->flash('success', 'Certificación creada correctamente');
        }

        $this->closeCertificationModal();
        $this->dispatch('certificationUpdated');
    }

    public function openUserCertificationModal($userId = null, $userCertificationId = null)
    {
        $this->userCertificationId = $userCertificationId;
        $this->userCertificationUserId = $userId;
        
        if ($userCertificationId) {
            $uc = UserCertification::find($userCertificationId);
            $this->userCertificationUserId = $uc->user_id;
            $this->userCertificationCertificationId = $uc->certification_id;
            $this->userCertificationObtainedAt = $uc->obtained_at?->format('Y-m-d');
            $this->userCertificationExpiresAt = $uc->expires_at?->format('Y-m-d');
            $this->userCertificationCertificateNumber = $uc->certificate_number ?? '';
            $this->userCertificationStatus = $uc->status;
            $this->userCertificationPlannedDate = $uc->planned_date?->format('Y-m-d');
            $this->userCertificationPriority = $uc->priority;
            $this->userCertificationNotes = $uc->notes ?? '';
        } else {
            $this->resetUserCertificationForm();
        }
        
        $this->showUserCertificationModal = true;
    }

    public function closeUserCertificationModal()
    {
        $this->showUserCertificationModal = false;
        $this->resetUserCertificationForm();
    }

    public function saveUserCertification()
    {
        $validated = $this->validate([
            'userCertificationUserId' => 'required|exists:users,id',
            'userCertificationCertificationId' => 'required|exists:certifications,id',
            'userCertificationObtainedAt' => 'nullable|date',
            'userCertificationExpiresAt' => 'nullable|date|after_or_equal:userCertificationObtainedAt',
            'userCertificationCertificateNumber' => 'nullable|string|max:255',
            'userCertificationStatus' => 'required|in:active,expired,revoked,in_progress,planned',
            'userCertificationPlannedDate' => 'nullable|date',
            'userCertificationPriority' => 'integer|min:0|max:5',
            'userCertificationNotes' => 'nullable|string',
        ], [], [
            'userCertificationUserId' => 'usuario',
            'userCertificationCertificationId' => 'certificación',
            'userCertificationObtainedAt' => 'fecha de obtención',
            'userCertificationExpiresAt' => 'fecha de vencimiento',
            'userCertificationStatus' => 'estado',
            'userCertificationPlannedDate' => 'fecha planificada',
            'userCertificationPriority' => 'prioridad',
        ]);

        $data = [
            'user_id' => $validated['userCertificationUserId'],
            'certification_id' => $validated['userCertificationCertificationId'],
            'obtained_at' => $validated['userCertificationObtainedAt'] ?: now(),
            'expires_at' => $validated['userCertificationExpiresAt'],
            'certificate_number' => $validated['userCertificationCertificateNumber'],
            'status' => $validated['userCertificationStatus'],
            'planned_date' => $validated['userCertificationPlannedDate'],
            'priority' => $validated['userCertificationPriority'],
            'notes' => $validated['userCertificationNotes'],
        ];

        if ($this->userCertificationId) {
            UserCertification::find($this->userCertificationId)->update($data);
            session()->flash('success', 'Certificación de usuario actualizada correctamente');
        } else {
            UserCertification::create($data);
            session()->flash('success', 'Certificación asignada correctamente');
        }

        $this->closeUserCertificationModal();
        $this->dispatch('certificationUpdated');
    }

    public function deleteUserCertification($userCertificationId)
    {
        UserCertification::find($userCertificationId)->delete();
        session()->flash('success', 'Certificación eliminada correctamente');
        $this->dispatch('certificationUpdated');
    }

    public function resetCertificationForm()
    {
        $this->certificationId = null;
        $this->certificationName = '';
        $this->certificationCode = '';
        $this->certificationDescription = '';
        $this->certificationProvider = '';
        $this->certificationCategory = '';
        $this->certificationLevel = '';
        $this->certificationValidityMonths = null;
        $this->certificationCost = null;
        $this->certificationDifficultyScore = null;
        $this->certificationValueScore = null;
        $this->certificationIsCritical = false;
        $this->certificationIsInternal = false;
        $this->certificationPointsReward = 0;
    }

    public function resetUserCertificationForm()
    {
        $this->userCertificationId = null;
        $this->userCertificationUserId = null;
        $this->userCertificationCertificationId = null;
        $this->userCertificationObtainedAt = null;
        $this->userCertificationExpiresAt = null;
        $this->userCertificationCertificateNumber = '';
        $this->userCertificationStatus = 'active';
        $this->userCertificationPlannedDate = null;
        $this->userCertificationPriority = 0;
        $this->userCertificationNotes = '';
    }

    public function render()
    {
        // Obtener usuarios del área
        $teamUsers = User::where('area_id', $this->plan->area_id)
            ->with(['internalRole', 'userCertifications.certification', 'certificationBadges'])
            ->orderBy('name')
            ->get();

        // Obtener certificaciones
        $certificationsQuery = Certification::query();

        if ($this->search) {
            $certificationsQuery->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%')
                  ->orWhere('provider', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->category) {
            $certificationsQuery->where('category', $this->category);
        }

        if ($this->provider) {
            $certificationsQuery->where('provider', $this->provider);
        }

        if ($this->level) {
            $certificationsQuery->where('level', $this->level);
        }

        if ($this->showCriticalOnly) {
            $certificationsQuery->where('is_critical', true);
        }

        $certifications = $certificationsQuery->withCount('userCertifications')
            ->orderBy('name')
            ->get();

        // Obtener todas las certificaciones de usuarios del área
        $userCertifications = UserCertification::whereIn('user_id', $teamUsers->pluck('id'))
            ->with(['user', 'certification'])
            ->get();

        // Estadísticas
        $stats = [
            'total_certifications' => $certifications->count(),
            'total_user_certifications' => $userCertifications->where('status', 'active')->count(),
            'expiring_soon' => $userCertifications->filter(function($uc) {
                return $uc->expiry_status === 'expiring_soon';
            })->count(),
            'expired' => $userCertifications->filter(function($uc) {
                return $uc->expiry_status === 'expired';
            })->count(),
            'planned' => $userCertifications->where('status', 'planned')->count(),
            'total_cost' => $userCertifications->where('status', 'active')
                ->sum(function($uc) {
                    return $uc->certification->cost ?? 0;
                }),
        ];

        // Leaderboard (por puntos de gamificación)
        $leaderboard = $teamUsers->map(function($user) {
            return [
                'user' => $user,
                'points' => $user->total_certification_points,
                'active_certifications' => $user->userCertifications->where('status', 'active')->count(),
                'badges' => $user->certificationBadges->count(),
            ];
        })->sortByDesc('points')->values();

        // Badges recientes
        $recentBadges = CertificationBadge::whereIn('user_id', $teamUsers->pluck('id'))
            ->with(['user', 'certification'])
            ->orderBy('earned_at', 'desc')
            ->limit(10)
            ->get();

        // Roadmap personalizado (si hay usuario seleccionado)
        $personalRoadmap = null;
        if ($this->selectedUserId) {
            $user = $teamUsers->firstWhere('id', $this->selectedUserId);
            if ($user) {
                $personalRoadmap = $user->userCertifications()
                    ->whereIn('status', ['planned', 'in_progress'])
                    ->with('certification')
                    ->orderBy('priority', 'desc')
                    ->orderBy('planned_date')
                    ->get();
            }
        }

        // Matriz por rol
        $roles = InternalRole::whereHas('users', function($q) use ($teamUsers) {
            $q->whereIn('id', $teamUsers->pluck('id'));
        })->with(['users.userCertifications.certification'])->get();

        $matrixByRole = $roles->map(function($role) use ($certifications, $teamUsers) {
            $roleUsers = $role->users->whereIn('id', $teamUsers->pluck('id'));
            return [
                'role' => $role,
                'users' => $roleUsers,
                'certifications' => $certifications->map(function($cert) use ($roleUsers) {
                    $usersWithCert = $roleUsers->filter(function($user) use ($cert) {
                        return $user->userCertifications->where('certification_id', $cert->id)
                            ->where('status', 'active')->isNotEmpty();
                    });
                    return [
                        'certification' => $cert,
                        'users_count' => $usersWithCert->count(),
                        'users' => $usersWithCert,
                        'coverage' => $roleUsers->count() > 0 
                            ? ($usersWithCert->count() / $roleUsers->count()) * 100 
                            : 0,
                    ];
                }),
            ];
        });

        // Categorías, proveedores y niveles disponibles
        $categories = Certification::whereNotNull('category')->distinct()->pluck('category')->sort()->values();
        $providers = Certification::whereNotNull('provider')->distinct()->pluck('provider')->sort()->values();
        $levels = Certification::whereNotNull('level')->distinct()->pluck('level')->sort()->values();

        return view('livewire.plans.plan-desarrollo-certificaciones', [
            'teamUsers' => $teamUsers,
            'certifications' => $certifications,
            'userCertifications' => $userCertifications,
            'stats' => $stats,
            'leaderboard' => $leaderboard,
            'recentBadges' => $recentBadges,
            'personalRoadmap' => $personalRoadmap,
            'matrixByRole' => $matrixByRole,
            'categories' => $categories,
            'providers' => $providers,
            'levels' => $levels,
        ]);
    }
}
