<?php

namespace App\Livewire\Profile;

use App\Models\User;
use App\Models\Competency;
use App\Models\Certification;
use App\Models\UserCertification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserProfile extends Component
{
    public ?int $userId = null;
    public User $user;
    public string $activeTab = 'info';
    
    // Información básica
    public string $name = '';
    public ?string $bio = null;
    public ?string $phone = null;
    public ?string $avatar_url = null;
    public ?string $joined_at = null;
    public ?string $last_evaluation_at = null;
    
    // Disponibilidad y preferencias
    public int $availability_percent = 100;
    public array $work_preferences = [];
    
    // Objetivos profesionales
    public ?string $career_goals = null;
    
    // Competencias
    public $competencies = [];
    
    // Certificaciones
    public $certifications = [];
    
    // Modal de certificación
    public $showCertificationModal = false;
    public $userCertificationId = null;
    public $userCertificationCertificationId = null;
    public $userCertificationObtainedAt = null;
    public $userCertificationExpiresAt = null;
    public $userCertificationCertificateNumber = '';
    public $userCertificationStatus = 'active';
    public $userCertificationPlannedDate = null;
    public $userCertificationPriority = 0;
    public $userCertificationNotes = '';
    
    public function mount(?int $userId = null)
    {
        $this->userId = $userId ?? Auth::id();
        $this->user = User::findOrFail($this->userId);
        $this->loadUserData();
        $this->updateProfileCompletion();
    }
    
    public function loadUser()
    {
        $this->loadUserData();
    }

    public function loadUserData()
    {
        $this->name = $this->user->name;
        $this->bio = $this->user->bio;
        $this->phone = $this->user->phone;
        $this->avatar_url = $this->user->avatar_url;
        $this->joined_at = $this->user->joined_at?->format('Y-m-d');
        $this->last_evaluation_at = $this->user->last_evaluation_at?->format('Y-m-d');
        $this->availability_percent = $this->user->availability_percent ?? 100;
        $this->work_preferences = $this->user->work_preferences ?? [];
        $this->career_goals = $this->user->career_goals;
        
        // Cargar competencias con niveles
        $this->competencies = $this->user->competencies()
            ->withPivot('current_level', 'target_level', 'last_assessed_at')
            ->get()
            ->map(function($competency) {
                return [
                    'id' => $competency->id,
                    'name' => $competency->name,
                    'category' => $competency->category,
                    'current_level' => $competency->pivot->current_level ?? 0,
                    'target_level' => $competency->pivot->target_level ?? 0,
                    'last_assessed_at' => $competency->pivot->last_assessed_at?->format('Y-m-d'),
                ];
            })
            ->toArray();
        
        // Cargar certificaciones
        $this->certifications = $this->user->userCertifications()
            ->with('certification')
            ->get()
            ->map(function($uc) {
                return [
                    'id' => $uc->id,
                    'certification_id' => $uc->certification_id,
                    'name' => $uc->certification->name,
                    'provider' => $uc->certification->provider,
                    'image_url' => $uc->certification->image_url,
                    'status' => $uc->status,
                    'obtained_at' => $uc->obtained_at?->format('Y-m-d'),
                    'expires_at' => $uc->expires_at?->format('Y-m-d'),
                    'planned_date' => $uc->planned_date?->format('Y-m-d'),
                    'certificate_number' => $uc->certificate_number,
                ];
            })
            ->toArray();
    }
    
    public function saveBasicInfo()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'phone' => 'nullable|string|max:20',
            'avatar_url' => 'nullable|url|max:255',
            'joined_at' => 'nullable|date',
            'last_evaluation_at' => 'nullable|date',
        ]);
        
        $this->user->update($validated);
        $this->updateProfileCompletion();
        
        session()->flash('message', 'Información básica actualizada correctamente.');
    }
    
    public function saveAvailability()
    {
        $validated = $this->validate([
            'availability_percent' => 'required|integer|min:0|max:100',
            'work_preferences' => 'nullable|array',
        ]);
        
        $this->user->update($validated);
        $this->updateProfileCompletion();
        
        session()->flash('message', 'Disponibilidad y preferencias actualizadas correctamente.');
    }
    
    public function saveCareerGoals()
    {
        $validated = $this->validate([
            'career_goals' => 'nullable|string|max:2000',
        ]);
        
        $this->user->update($validated);
        $this->updateProfileCompletion();
        
        session()->flash('message', 'Objetivos profesionales actualizados correctamente.');
    }
    
    public function updateProfileCompletion()
    {
        $fields = [
            'name' => !empty($this->user->name),
            'email' => !empty($this->user->email),
            'bio' => !empty($this->user->bio),
            'phone' => !empty($this->user->phone),
            'joined_at' => !empty($this->user->joined_at),
            'internal_role_id' => !empty($this->user->internal_role_id),
            'area_id' => !empty($this->user->area_id),
            'career_goals' => !empty($this->user->career_goals),
            'competencies' => $this->user->competencies()->count() > 0,
            'certifications' => $this->user->userCertifications()->count() > 0,
        ];
        
        $completed = array_sum($fields);
        $total = count($fields);
        $percentage = round(($completed / $total) * 100);
        
        $this->user->update(['profile_completion_percent' => $percentage]);
    }
    
    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }
    
    public function openCertificationModal($userCertificationId = null)
    {
        $this->userCertificationId = $userCertificationId;
        
        if ($userCertificationId) {
            $uc = UserCertification::with('certification')->find($userCertificationId);
            $this->userCertificationCertificationId = $uc->certification_id;
            $this->userCertificationObtainedAt = $uc->obtained_at?->format('Y-m-d');
            $this->userCertificationExpiresAt = $uc->expires_at?->format('Y-m-d');
            $this->userCertificationCertificateNumber = $uc->certificate_number ?? '';
            $this->userCertificationStatus = $uc->status;
            $this->userCertificationPlannedDate = $uc->planned_date?->format('Y-m-d');
            $this->userCertificationPriority = $uc->priority;
            $this->userCertificationNotes = $uc->notes ?? '';
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
    
    public function resetCertificationForm()
    {
        $this->userCertificationId = null;
        $this->userCertificationCertificationId = null;
        $this->userCertificationObtainedAt = null;
        $this->userCertificationExpiresAt = null;
        $this->userCertificationCertificateNumber = '';
        $this->userCertificationStatus = 'active';
        $this->userCertificationPlannedDate = null;
        $this->userCertificationPriority = 0;
        $this->userCertificationNotes = '';
    }
    
    public function saveCertification()
    {
        $validated = $this->validate([
            'userCertificationCertificationId' => 'required|exists:certifications,id',
            'userCertificationObtainedAt' => 'nullable|date',
            'userCertificationExpiresAt' => 'nullable|date|after_or_equal:userCertificationObtainedAt',
            'userCertificationCertificateNumber' => 'nullable|string|max:255',
            'userCertificationStatus' => 'required|in:active,expired,revoked,in_progress,planned',
            'userCertificationPlannedDate' => 'nullable|date',
            'userCertificationPriority' => 'integer|min:0|max:5',
            'userCertificationNotes' => 'nullable|string',
        ], [], [
            'userCertificationCertificationId' => 'certificación',
            'userCertificationObtainedAt' => 'fecha de obtención',
            'userCertificationExpiresAt' => 'fecha de vencimiento',
            'userCertificationStatus' => 'estado',
            'userCertificationPlannedDate' => 'fecha planificada',
            'userCertificationPriority' => 'prioridad',
        ]);

        $data = [
            'user_id' => $this->user->id,
            'certification_id' => $validated['userCertificationCertificationId'],
            'obtained_at' => $validated['userCertificationObtainedAt'] ?: ($validated['userCertificationStatus'] === 'active' ? now() : null),
            'expires_at' => $validated['userCertificationExpiresAt'],
            'certificate_number' => $validated['userCertificationCertificateNumber'],
            'status' => $validated['userCertificationStatus'],
            'planned_date' => $validated['userCertificationPlannedDate'],
            'priority' => $validated['userCertificationPriority'],
            'notes' => $validated['userCertificationNotes'],
        ];

        if ($this->userCertificationId) {
            UserCertification::find($this->userCertificationId)->update($data);
            session()->flash('message', 'Certificación actualizada correctamente');
        } else {
            // Verificar que no exista ya esta certificación para este usuario
            $exists = UserCertification::where('user_id', $this->user->id)
                ->where('certification_id', $validated['userCertificationCertificationId'])
                ->exists();
            
            if ($exists) {
                session()->flash('error', 'Ya tienes esta certificación vinculada');
                return;
            }
            
            UserCertification::create($data);
            session()->flash('message', 'Certificación añadida correctamente');
        }

        $this->closeCertificationModal();
        $this->loadUserData();
        $this->updateProfileCompletion();
    }
    
    public function deleteCertification($userCertificationId)
    {
        UserCertification::find($userCertificationId)->delete();
        session()->flash('message', 'Certificación eliminada correctamente');
        $this->loadUserData();
        $this->updateProfileCompletion();
    }
    
    public function render()
    {
        $this->user->refresh();
        $this->user->load(['internalRole', 'area', 'manager', 'competencies', 'userCertifications.certification']);
        
        // Obtener certificaciones disponibles
        // Si estamos editando, incluir la certificación actual; si no, excluir las que ya tiene
        $userCertificationIds = $this->user->userCertifications()->pluck('certification_id');
        $availableCertificationsQuery = Certification::where('is_active', true);
        
        if ($this->userCertificationId) {
            // Al editar, incluir la certificación actual
            $currentCertId = UserCertification::find($this->userCertificationId)?->certification_id;
            if ($currentCertId) {
                $availableCertificationsQuery->where(function($q) use ($userCertificationIds, $currentCertId) {
                    $q->whereNotIn('id', $userCertificationIds)
                      ->orWhere('id', $currentCertId);
                });
            }
        } else {
            // Al crear, excluir las que ya tiene
            $availableCertificationsQuery->whereNotIn('id', $userCertificationIds);
        }
        
        $availableCertifications = $availableCertificationsQuery
            ->orderBy('category')
            ->orderBy('name')
            ->get();
        
        return view('livewire.profile.user-profile', [
            'availableCompetencies' => Competency::where('is_active', true)
                ->where(function($q) {
                    $q->where('area_id', $this->user->area_id)
                      ->orWhereNull('area_id');
                })
                ->orderBy('category')
                ->orderBy('name')
                ->get(),
            'availableCertifications' => $availableCertifications,
        ]);
    }
}
