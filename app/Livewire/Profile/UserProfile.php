<?php

namespace App\Livewire\Profile;

use App\Models\User;
use App\Models\Competency;
use App\Models\Certification;
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
                    'status' => $uc->status,
                    'obtained_at' => $uc->obtained_at?->format('Y-m-d'),
                    'expires_at' => $uc->expires_at?->format('Y-m-d'),
                    'planned_date' => $uc->planned_date?->format('Y-m-d'),
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
    
    public function render()
    {
        $this->user->refresh();
        $this->user->load(['internalRole', 'area', 'manager', 'competencies', 'userCertifications.certification']);
        
        return view('livewire.profile.user-profile', [
            'availableCompetencies' => Competency::where('is_active', true)
                ->where(function($q) {
                    $q->where('area_id', $this->user->area_id)
                      ->orWhereNull('area_id');
                })
                ->orderBy('category')
                ->orderBy('name')
                ->get(),
            'availableCertifications' => Certification::where('is_active', true)
                ->orderBy('category')
                ->orderBy('name')
                ->get(),
        ]);
    }
}
