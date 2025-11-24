<?php

namespace App\Livewire\Plans;

use App\Models\Plan;
use App\Models\Certification;
use App\Models\User;
use App\Models\UserCertification;
use App\Models\CertificationBadge;
use App\Models\InternalRole;
use App\Models\CertificationAttribute;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class PlanDesarrolloCertificaciones extends Component
{
    use WithPagination, WithFileUploads;

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
    public $showMatrixDetailModal = false;
    public $selectedMatrixCertification = null;
    public $matrixDetailSearch = '';
    public $matrixDetailFilter = 'all'; // all, expired, expiring_soon, valid, permanent
    
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
    public $certificationUrl = '';
    public $certificationImage = null;
    public $certificationImageUrl = null;
    public $showCertificationCropper = false;
    public $certificationCropData = null;

    public array $attributeOptions = [];
    
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
        $this->attributeOptions = $this->loadAttributeOptions();
        $this->ensureValidCertificationAttributes();
    }

    protected function attributeTypeMap(): array
    {
        return [
            'provider' => 'provider',
            'category' => 'category',
            'level' => 'level',
        ];
    }

    protected function loadAttributeOptions(): array
    {
        // Usar directamente CertificationAttribute::optionsFor() que ya tiene su propio cache
        // Esto asegura que siempre obtenemos las opciones más recientes
        $options = [];
        foreach ($this->attributeTypeMap() as $key => $type) {
            $options[$key] = CertificationAttribute::optionsFor($type);
        }
        return $options;
    }

    public function refreshAttributeOptions(): void
    {
        // Limpiar el cache del modelo para forzar la recarga
        CertificationAttribute::forgetCache();
        // Recargar las opciones
        $this->attributeOptions = $this->loadAttributeOptions();
        $this->ensureValidCertificationAttributes();
    }

    protected function ensureValidCertificationAttributes(): void
    {
        $this->certificationProvider = $this->resolveAttributeValue('provider', $this->certificationProvider, true);
        $this->certificationCategory = $this->resolveAttributeValue('category', $this->certificationCategory);
        $this->certificationLevel = $this->resolveAttributeValue('level', $this->certificationLevel);

        $this->category = $this->resolveAttributeValue('category', $this->category);
        $this->provider = $this->resolveAttributeValue('provider', $this->provider);
        $this->level = $this->resolveAttributeValue('level', $this->level);
    }

    protected function resolveAttributeValue(string $type, ?string $value, bool $fallbackToFirst = false): ?string
    {
        $options = array_keys($this->attributeOptions[$type] ?? []);
        if (empty($options)) {
            return $fallbackToFirst ? null : $value;
        }

        if ($value && in_array($value, $options, true)) {
            return $value;
        }

        return $fallbackToFirst ? $options[0] : null;
    }

    protected function attributeLabel(string $type, ?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        return $this->attributeOptions[$type][$value] ?? $value;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
        $this->category = $this->resolveAttributeValue('category', $this->category);
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingProvider()
    {
        $this->resetPage();
    }

    public function updatingLevel()
    {
        $this->resetPage();
    }

    public function updatedCertificationProvider(): void
    {
        $this->certificationProvider = $this->resolveAttributeValue('provider', $this->certificationProvider, true);
    }

    public function updatedCertificationCategory(): void
    {
        $this->certificationCategory = $this->resolveAttributeValue('category', $this->certificationCategory);
    }

    public function updatedCertificationLevel(): void
    {
        $this->certificationLevel = $this->resolveAttributeValue('level', $this->certificationLevel);
    }

    public function updatedProvider(): void
    {
        $this->provider = $this->resolveAttributeValue('provider', $this->provider);
    }

    public function updatedLevel(): void
    {
        $this->level = $this->resolveAttributeValue('level', $this->level);
    }

    public function openCertificationModal($certificationId = null)
    {
        // Refrescar opciones de atributos antes de abrir el modal
        $this->refreshAttributeOptions();
        
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
            $this->certificationUrl = $cert->official_url ?? '';
            $this->certificationImageUrl = $cert->image_url;
        } else {
            $this->resetCertificationForm();
        }
        
        $this->ensureValidCertificationAttributes();
        
        $this->showCertificationModal = true;
    }

    public function closeCertificationModal()
    {
        $this->showCertificationModal = false;
        $this->resetCertificationForm();
    }

    public function updatedCertificationImage()
    {
        try {
            $this->validate([
                'certificationImage' => 'required|image|max:2048', // 2MB max
            ], [
                'certificationImage.required' => 'Por favor selecciona una imagen.',
                'certificationImage.image' => 'El archivo debe ser una imagen.',
                'certificationImage.max' => 'La imagen no debe superar los 2MB.',
            ]);
            
            if ($this->certificationImage) {
                $this->showCertificationCropper = true;
                $this->dispatch('certification-image-uploaded');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            session()->flash('error', $e->getMessage());
            $this->reset('certificationImage');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al subir la imagen: ' . $e->getMessage());
            $this->reset('certificationImage');
        }
    }

    public function cropCertificationImage($cropData)
    {
        $this->certificationCropData = $cropData;
    }

    public function saveCroppedCertificationImage()
    {
        if (!$this->certificationCropData) {
            session()->flash('error', 'No hay imagen recortada para guardar.');
            return;
        }

        try {
            // Decodificar los datos del crop (base64)
            $imageData = explode(',', $this->certificationCropData);
            $imageData = base64_decode($imageData[1] ?? $imageData[0]);
            
            // Asegurar que el directorio existe
            if (!Storage::disk('public')->exists('certifications')) {
                Storage::disk('public')->makeDirectory('certifications');
            }
            
            // Generar nombre único para el archivo
            $filename = 'certifications/' . time() . '_certification.jpg';
            
            // Guardar en storage público
            Storage::disk('public')->put($filename, $imageData);
            
            // Eliminar imagen anterior si existe
            if ($this->certificationId) {
                $cert = Certification::find($this->certificationId);
                if ($cert && $cert->image_url) {
                    $oldPath = str_replace('/storage/', '', parse_url($cert->image_url, PHP_URL_PATH));
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }
            }
            
            // Actualizar URL de la imagen
            $this->certificationImageUrl = asset('storage/' . $filename);
            
            // Si estamos editando, actualizar directamente
            if ($this->certificationId) {
                $cert = Certification::find($this->certificationId);
                if ($cert) {
                    $cert->update(['image_url' => $this->certificationImageUrl]);
                }
            }
            
            $this->reset(['certificationImage', 'showCertificationCropper', 'certificationCropData']);
            
            session()->flash('success', 'Imagen recortada y guardada correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar la imagen recortada: ' . $e->getMessage());
            Log::error('Error al guardar imagen recortada de certificación: ' . $e->getMessage());
        }
    }

    public function cancelCertificationCrop()
    {
        $this->reset(['certificationImage', 'showCertificationCropper', 'certificationCropData']);
    }

    public function openMatrixDetailModal($certificationId)
    {
        $this->selectedMatrixCertification = $certificationId;
        $this->showMatrixDetailModal = true;
    }

    public function closeMatrixDetailModal()
    {
        $this->showMatrixDetailModal = false;
        $this->selectedMatrixCertification = null;
        $this->matrixDetailSearch = '';
        $this->matrixDetailFilter = 'all';
    }

    public function saveCertification()
    {
        $validProviders = array_keys($this->attributeOptions['provider'] ?? []);
        $validCategories = array_keys($this->attributeOptions['category'] ?? []);
        $validLevels = array_keys($this->attributeOptions['level'] ?? []);

        $providerRules = ['required', 'string', 'max:255'];
        if (!empty($validProviders)) {
            $providerRules[] = Rule::in($validProviders);
        }

        $categoryRules = ['nullable', 'string', 'max:255'];
        if (!empty($validCategories)) {
            $categoryRules[] = Rule::in($validCategories);
        }

        $levelRules = ['nullable', 'string', 'max:255'];
        if (!empty($validLevels)) {
            $levelRules[] = Rule::in($validLevels);
        }

        $validated = $this->validate([
            'certificationName' => 'required|string|max:255',
            'certificationCode' => 'nullable|string|max:255|unique:certifications,code,' . $this->certificationId,
            'certificationDescription' => 'nullable|string',
            'certificationProvider' => $providerRules,
            'certificationCategory' => $categoryRules,
            'certificationLevel' => $levelRules,
            'certificationValidityMonths' => 'nullable|integer|min:0',
            'certificationCost' => 'nullable|numeric|min:0',
            'certificationDifficultyScore' => 'nullable|integer|min:1|max:10',
            'certificationValueScore' => 'nullable|integer|min:1|max:10',
            'certificationIsCritical' => 'boolean',
            'certificationIsInternal' => 'boolean',
            'certificationPointsReward' => 'integer|min:0',
            'certificationUrl' => 'nullable|url|max:500',
            'certificationImage' => 'nullable|image|max:2048', // 2MB max
        ]);

        $data = [
            'name' => $validated['certificationName'],
            'code' => $validated['certificationCode'] ?: null,
            'description' => $validated['certificationDescription'],
            'provider' => $validated['certificationProvider'],
            'category' => $validated['certificationCategory'] ?: null,
            'level' => $validated['certificationLevel'] ?: null,
            'validity_months' => $validated['certificationValidityMonths'],
            'cost' => $validated['certificationCost'],
            'difficulty_score' => $validated['certificationDifficultyScore'],
            'value_score' => $validated['certificationValueScore'],
            'is_critical' => $validated['certificationIsCritical'],
            'is_internal' => $validated['certificationIsInternal'],
            'points_reward' => $validated['certificationPointsReward'],
            'official_url' => $validated['certificationUrl'] ?: null,
        ];

        // Manejar subida de imagen
        // Si ya hay una imagen recortada guardada (certificationImageUrl actualizado), usarla
        if ($this->certificationImageUrl && str_contains($this->certificationImageUrl, 'storage/certifications/')) {
            $data['image_url'] = $this->certificationImageUrl;
        } elseif ($this->certificationCropData) {
        // Si hay una imagen recortada pero no guardada, procesarla
            try {
                // Decodificar los datos del crop (base64)
                $imageData = explode(',', $this->certificationCropData);
                $imageData = base64_decode($imageData[1] ?? $imageData[0]);
                
                // Asegurar que el directorio existe
                if (!Storage::disk('public')->exists('certifications')) {
                    Storage::disk('public')->makeDirectory('certifications');
                }
                
                // Eliminar imagen anterior si existe
                if ($this->certificationId) {
                    $cert = Certification::find($this->certificationId);
                    if ($cert && $cert->image_url) {
                        $oldPath = str_replace('/storage/', '', parse_url($cert->image_url, PHP_URL_PATH));
                        if (Storage::disk('public')->exists($oldPath)) {
                            Storage::disk('public')->delete($oldPath);
                        }
                    }
                }
                
                // Generar nombre único para el archivo
                $filename = 'certifications/' . time() . '_certification.jpg';
                
                // Guardar en storage público
                Storage::disk('public')->put($filename, $imageData);
                
                $data['image_url'] = asset('storage/' . $filename);
                
                // Resetear el crop data después de usarlo
                $this->certificationCropData = null;
            } catch (\Exception $e) {
                session()->flash('error', 'Error al guardar la imagen recortada: ' . $e->getMessage());
                Log::error('Error al guardar imagen recortada: ' . $e->getMessage());
                return;
            }
        } elseif ($this->certificationImage) {
        // Verificar si hay una nueva imagen subida (sin crop)
            try {
                // Log para debug
                Log::info('Procesando imagen de certificación', [
                    'certification_id' => $this->certificationId,
                    'file_name' => $this->certificationImage->getClientOriginalName(),
                    'file_size' => $this->certificationImage->getSize(),
                    'is_valid' => $this->certificationImage->isValid(),
                ]);
                
                // Verificar que el archivo es válido
                if (!$this->certificationImage->isValid()) {
                    session()->flash('error', 'El archivo de imagen no es válido.');
                    Log::error('Archivo de imagen no válido', [
                        'certification_id' => $this->certificationId,
                        'file_name' => $this->certificationImage->getClientOriginalName(),
                    ]);
                    return;
                }
                
                // Asegurar que el directorio existe
                if (!Storage::disk('public')->exists('certifications')) {
                    Storage::disk('public')->makeDirectory('certifications');
                }
                
                // Eliminar imagen anterior si existe
                if ($this->certificationId) {
                    $cert = Certification::find($this->certificationId);
                    if ($cert && $cert->image_url) {
                        $oldPath = str_replace('/storage/', '', parse_url($cert->image_url, PHP_URL_PATH));
                        if (Storage::disk('public')->exists($oldPath)) {
                            Storage::disk('public')->delete($oldPath);
                        }
                    }
                }
                
                // Guardar nueva imagen
                // Sanitizar el nombre del archivo
                $originalName = $this->certificationImage->getClientOriginalName();
                $extension = $this->certificationImage->getClientOriginalExtension();
                $nameWithoutExtension = pathinfo($originalName, PATHINFO_FILENAME);
                $sanitizedName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $nameWithoutExtension);
                $filename = time() . '_' . $sanitizedName . '.' . $extension;
                
                $path = $this->certificationImage->storeAs('certifications', $filename, 'public');
                
                // Verificar que el archivo se guardó correctamente
                if (!$path || !Storage::disk('public')->exists($path)) {
                    session()->flash('error', 'Error al guardar la imagen en el servidor.');
                    return;
                }
                
                // El método storeAs devuelve la ruta relativa desde storage/app/public
                // Por lo tanto, $path será algo como "certifications/1234567890_imagen.jpg"
                // Y asset('storage/' . $path) generará la URL completa
                $data['image_url'] = asset('storage/' . $path);
                
                // Log para debug
                Log::info('Imagen guardada correctamente', [
                    'certification_id' => $this->certificationId,
                    'path' => $path,
                    'image_url' => $data['image_url'],
                ]);
            } catch (\Exception $e) {
                session()->flash('error', 'Error al subir la imagen: ' . $e->getMessage());
                Log::error('Error al subir imagen de certificación: ' . $e->getMessage(), [
                    'certification_id' => $this->certificationId,
                    'file_name' => $this->certificationImage?->getClientOriginalName(),
                ]);
                return;
            }
        } elseif ($this->certificationId) {
            // Si estamos editando y no hay nueva imagen, mantener la imagen existente
            $cert = Certification::find($this->certificationId);
            if ($cert && $cert->image_url) {
                $data['image_url'] = $cert->image_url;
            }
        }

        if ($this->certificationId) {
            $cert = Certification::find($this->certificationId);
            $cert->update($data);
            
            // Verificar que la imagen se guardó correctamente
            $cert->refresh();
            if (isset($data['image_url']) && $cert->image_url !== $data['image_url']) {
                Log::warning('La imagen no se guardó correctamente', [
                    'expected' => $data['image_url'],
                    'actual' => $cert->image_url,
                ]);
            }
            
            session()->flash('success', 'Certificación actualizada correctamente');
        } else {
            Certification::create($data);
            session()->flash('success', 'Certificación creada correctamente');
        }

        $this->closeCertificationModal();
        $this->dispatch('certificationUpdated');
    }

    public function deleteCertification($certificationId)
    {
        $cert = Certification::find($certificationId);
        if ($cert) {
            $cert->delete();
            session()->flash('success', 'Certificación eliminada correctamente');
            $this->dispatch('certificationUpdated');
        }
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
        $this->certificationProvider = $this->resolveAttributeValue('provider', null, true) ?? '';
        $this->certificationCategory = $this->resolveAttributeValue('category', null);
        $this->certificationLevel = $this->resolveAttributeValue('level', null);
        $this->certificationValidityMonths = null;
        $this->certificationCost = null;
        $this->certificationDifficultyScore = null;
        $this->certificationValueScore = null;
        $this->certificationIsCritical = false;
        $this->certificationIsInternal = false;
        $this->certificationPointsReward = 0;
        $this->certificationUrl = '';
        $this->certificationImage = null;
        $this->certificationImageUrl = null;
        $this->showCertificationCropper = false;
        $this->certificationCropData = null;
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
        // Asegurar que las opciones están cargadas y actualizadas
        if (empty($this->attributeOptions)) {
            $this->attributeOptions = $this->loadAttributeOptions();
        }
        
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
        $roadmapTimeline = null;
        if ($this->selectedUserId) {
            $user = $teamUsers->firstWhere('id', $this->selectedUserId);
            if ($user) {
                $personalRoadmap = $user->userCertifications()
                    ->whereIn('status', ['planned', 'in_progress', 'active'])
                    ->with('certification')
                    ->orderBy('planned_date')
                    ->orderBy('priority', 'desc')
                    ->get();
                
                // Preparar datos para timeline visual
                $roadmapTimeline = $personalRoadmap->map(function($uc) {
                    $date = $uc->planned_date ?? $uc->obtained_at ?? now();
                    return [
                        'id' => $uc->id,
                        'certification' => $uc->certification,
                        'name' => $uc->certification->name,
                        'provider' => $uc->certification->provider,
                        'provider_label' => CertificationAttribute::labelFor('provider', $uc->certification->provider) ?? $uc->certification->provider,
                        'image_url' => $uc->certification->image_url,
                        'date' => $date->format('Y-m-d'),
                        'date_formatted' => $date->format('d/m/Y'),
                        'status' => $uc->status,
                        'priority' => $uc->priority,
                        'notes' => $uc->notes,
                        'is_critical' => $uc->certification->is_critical ?? false,
                        'month' => $date->format('Y-m'),
                        'month_label' => $date->locale('es')->translatedFormat('F Y'),
                        'day' => $date->format('d'),
                    ];
                })->groupBy('month')->map(function($items, $month) {
                    return [
                        'month' => $month,
                        'month_label' => $items->first()['month_label'],
                        'items' => $items->sortBy('date')->values(),
                    ];
                })->sortBy('month')->values();
            }
        }

        // Matriz de certificaciones del equipo - solo las que alguien tiene
        // Obtener todas las certificaciones que tiene al menos un usuario del equipo
        $teamCertificationIds = UserCertification::whereIn('user_id', $teamUsers->pluck('id'))
            ->where('status', 'active')
            ->distinct()
            ->pluck('certification_id');
        
        $teamCertifications = Certification::whereIn('id', $teamCertificationIds)
            ->with(['userCertifications' => function($q) use ($teamUsers) {
                $q->whereIn('user_id', $teamUsers->pluck('id'))
                  ->where('status', 'active')
                  ->with('user');
            }])
            ->orderBy('name')
            ->get();
        
        // Preparar datos de la matriz: certificación -> usuarios que la tienen
        $matrixData = $teamCertifications->map(function($cert) use ($teamUsers) {
            $usersWithCert = $cert->userCertifications->map(function($uc) {
                return [
                    'user' => $uc->user,
                    'obtained_at' => $uc->obtained_at,
                    'expires_at' => $uc->expires_at,
                    'certificate_number' => $uc->certificate_number,
                ];
            });
            
            return [
                'certification' => $cert,
                'users' => $usersWithCert,
                'users_count' => $usersWithCert->count(),
            ];
        });

        // Opciones gestionadas desde /admin
        $categoryOptions = $this->attributeOptions['category'] ?? [];
        $providerOptions = $this->attributeOptions['provider'] ?? [];
        $levelOptions = $this->attributeOptions['level'] ?? [];

        return view('livewire.plans.plan-desarrollo-certificaciones', [
            'teamUsers' => $teamUsers,
            'certifications' => $certifications,
            'userCertifications' => $userCertifications,
            'stats' => $stats,
            'leaderboard' => $leaderboard,
            'recentBadges' => $recentBadges,
            'personalRoadmap' => $personalRoadmap,
            'roadmapTimeline' => $roadmapTimeline,
            'matrixData' => $matrixData,
            'categoryOptions' => $categoryOptions,
            'providerOptions' => $providerOptions,
            'levelOptions' => $levelOptions,
        ]);
    }
}
