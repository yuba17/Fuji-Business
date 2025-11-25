<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\PlanType;
use App\Models\Area;
use App\Services\PlanTemplateService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $this->authorize('viewAny', Plan::class);
        
        return view('plans.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create', Plan::class);
        
        $planTypes = PlanType::where('is_active', true)->orderBy('order')->get();
        $areas = Area::where('is_active', true)->orderBy('order')->get();
        
        return view('plans.create', compact('planTypes', 'areas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Plan::class);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'plan_type_id' => 'required|exists:plan_types,id',
            'area_id' => 'required|exists:areas,id',
            'status' => 'nullable|in:draft,internal_review,director_review,approved,in_progress,under_review,closed,archived',
            'start_date' => 'nullable|date',
            'target_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $validated['manager_id'] = auth()->id();
        $validated['version'] = '1.0';
        $validated['is_current_version'] = true;

        $plan = Plan::create($validated);

        // Crear secciones desde el template del tipo de plan
        $planType = PlanType::find($validated['plan_type_id']);
        if ($planType) {
            $templateService = new PlanTemplateService();
            $templateService->createSectionsFromTemplate($plan, $planType);
        }

        return redirect()->route('plans.show', $plan)
            ->with('success', 'Plan creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Plan $plan): View
    {
        $this->authorize('view', $plan);
        
        $plan->load([
            'planType',
            'area',
            'manager',
            'director',
            'sections' => function($q) {
                $q->orderBy('order');
            },
            'kpis',
            'milestones',
            'tasks',
            'risks',
        ]);

        // Optimización: Usar métodos del modelo cuando estén disponibles, con fallback seguro
        $isInternalPlan = $plan->planType
            ? ($plan->planType->isDesarrolloInterno() || Str::contains(Str::lower($plan->planType->name ?? ''), 'desarrollo interno'))
            : false;
        $isCommercialPlan = $plan->planType
            ? ($plan->planType->isComercial() || Str::contains(Str::lower($plan->planType->name ?? ''), 'comercial'))
            : false;

        $availableTabs = ['summary'];
        if ($isInternalPlan) {
            $availableTabs = array_merge($availableTabs, [
                'organization',
                'competencies',
                'infrastructure',
                'certifications',
                'operational-roadmap',
                'innovation-tooling',
                'team-culture',
            ]);
        }
        if ($isCommercialPlan) {
            $availableTabs[] = 'sectorial';
        }

        $requestedTab = request()->query('tab');
        $activeTab = in_array($requestedTab, $availableTabs, true)
            ? $requestedTab
            : ($availableTabs[0] ?? 'summary');

        $teamUsers = null;
        $groupedByInternalRole = null;
        $serviceLines = null;
        $capacityHeatmap = null;
        $talentPyramid = null;
            $organizationStats = null;
            $profileStats = null;

        if ($isInternalPlan && $plan->area) {
            $teamUsers = $plan->area->users()
                ->with(['internalRole', 'area', 'roles', 'serviceLines', 'manager', 'competencies', 'userCertifications'])
                ->orderBy('name')
                ->get();

            $groupedByInternalRole = $teamUsers
                ->groupBy(fn ($user) => optional($user->internalRole)->name ?? 'Sin rol interno');

            $serviceLines = $plan->area->serviceLines()
                ->with(['manager', 'users' => function($q) {
                    $q->with('internalRole');
                }])
                ->orderBy('order')
                ->get();

            // Calcular heatmap de capacidad (líneas × niveles)
            $capacityHeatmap = [];
            $levels = ['director', 'manager', 'lead', 'senior', 'mid', 'junior'];
            
            foreach ($serviceLines as $line) {
                $lineData = [
                    'line' => $line,
                    'levels' => []
                ];
                
                foreach ($levels as $level) {
                    $count = $line->users->filter(function($user) use ($level) {
                        $userLevel = strtolower(optional($user->internalRole)->level ?? '');
                        $roleType = strtolower(optional($user->internalRole)->role_type ?? '');
                        // Director: nivel director o role_type director
                        if ($level === 'director') {
                            return $userLevel === 'director' || $roleType === 'director' || $user->hasRole('director');
                        }
                        // Manager: nivel manager o role_type manager (pero no director)
                        if ($level === 'manager') {
                            return ($userLevel === 'manager' || $roleType === 'manager' || $user->hasRole('manager')) 
                                   && !$user->hasRole('director') && $roleType !== 'director';
                        }
                        // Otros niveles: coincidencia exacta
                        return $userLevel === $level;
                    })->count();
                    
                    $lineData['levels'][$level] = $count;
                }
                
                // Calcular ratio manager:IC para alertas
                $managers = $lineData['levels']['manager'] + $lineData['levels']['director'];
                $ics = $lineData['levels']['senior'] + $lineData['levels']['mid'] + $lineData['levels']['junior'];
                $lineData['ratio'] = $ics > 0 && $managers > 0 ? round($ics / $managers, 1) : 0;
                $lineData['health'] = $lineData['ratio'] > 12 ? 'warning' : ($lineData['ratio'] > 8 ? 'caution' : 'good');
                
                $capacityHeatmap[] = $lineData;
            }

            // Calcular pirámide de talento (agrupación por nivel)
            $talentPyramid = [
                'director' => $teamUsers->filter(function($u) {
                    $level = strtolower(optional($u->internalRole)->level ?? '');
                    $roleType = strtolower(optional($u->internalRole)->role_type ?? '');
                    return $level === 'director' || $roleType === 'director' || $u->hasRole('director');
                })->count(),
                'manager' => $teamUsers->filter(function($u) {
                    $level = strtolower(optional($u->internalRole)->level ?? '');
                    $roleType = strtolower(optional($u->internalRole)->role_type ?? '');
                    return ($level === 'manager' || $roleType === 'manager' || $u->hasRole('manager'))
                           && !$u->hasRole('director') && $roleType !== 'director';
                })->count(),
                'lead' => $teamUsers->filter(fn($u) => strtolower(optional($u->internalRole)->level ?? '') === 'lead')->count(),
                'senior' => $teamUsers->filter(fn($u) => strtolower(optional($u->internalRole)->level ?? '') === 'senior')->count(),
                'mid' => $teamUsers->filter(fn($u) => strtolower(optional($u->internalRole)->level ?? '') === 'mid')->count(),
                'junior' => $teamUsers->filter(fn($u) => strtolower(optional($u->internalRole)->level ?? '') === 'junior')->count(),
            ];
            
            $total = array_sum($talentPyramid);
            foreach ($talentPyramid as $level => $count) {
                $talentPyramid[$level . '_pct'] = $total > 0 ? round(($count / $total) * 100, 1) : 0;
            }

            // Stats de organización
            $managers = $talentPyramid['director'] + $talentPyramid['manager'];
            $ics = $talentPyramid['senior'] + $talentPyramid['mid'] + $talentPyramid['junior'];
                $organizationStats = [
                    'total_people' => $teamUsers->count(),
                    'total_roles' => $groupedByInternalRole->keys()->count(),
                    'total_service_lines' => $serviceLines->count(),
                    'manager_ic_ratio' => $ics > 0 && $managers > 0 ? round($ics / $managers, 1) : 0,
                ];

                // Estadísticas de perfiles
                $profileStats = [
                    'avg_completion' => round($teamUsers->avg('profile_completion_percent') ?? 0, 1),
                    'complete_profiles' => $teamUsers->where('profile_completion_percent', '>=', 100)->count(),
                    'incomplete_profiles' => $teamUsers->where('profile_completion_percent', '<', 100)->count(),
                    'total_competencies' => $teamUsers->sum(fn($u) => $u->competencies->count()),
                    'total_certifications' => $teamUsers->sum(fn($u) => $u->userCertifications->count()),
                    'users_with_avatar' => $teamUsers->whereNotNull('avatar_url')->count(),
                    'users_without_avatar' => $teamUsers->whereNull('avatar_url')->count(),
                ];
            }
        
        return view('plans.show', compact(
            'plan',
            'teamUsers',
            'groupedByInternalRole',
            'serviceLines',
            'capacityHeatmap',
            'talentPyramid',
            'organizationStats',
            'profileStats',
            'activeTab',
            'isInternalPlan',
            'isCommercialPlan'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Plan $plan): View
    {
        $this->authorize('update', $plan);
        
        $planTypes = PlanType::where('is_active', true)->orderBy('order')->get();
        $areas = Area::where('is_active', true)->orderBy('order')->get();
        
        return view('plans.edit', compact('plan', 'planTypes', 'areas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plan $plan): RedirectResponse
    {
        $this->authorize('update', $plan);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'plan_type_id' => 'required|exists:plan_types,id',
            'area_id' => 'required|exists:areas,id',
            'status' => 'nullable|in:draft,internal_review,director_review,approved,in_progress,under_review,closed,archived',
            'start_date' => 'nullable|date',
            'target_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $plan->update($validated);

        return redirect()->route('plans.show', $plan)
            ->with('success', 'Plan actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plan $plan): RedirectResponse
    {
        $this->authorize('delete', $plan);
        
        $plan->delete();

        return redirect()->route('plans.index')
            ->with('success', 'Plan eliminado correctamente');
    }

    /**
     * Mostrar roadmap (vista Gantt) del plan
     */
    public function roadmap(Plan $plan): View
    {
        $this->authorize('view', $plan);
        
        $milestones = $plan->milestones()
            ->with(['responsible', 'tasks', 'predecessorDependencies', 'successorDependencies'])
            ->orderBy('order')
            ->orderBy('target_date')
            ->get();
        
        return view('plans.roadmap', compact('plan', 'milestones'));
    }
}
