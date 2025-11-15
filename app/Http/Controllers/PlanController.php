<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\PlanType;
use App\Models\Area;
use App\Services\PlanTemplateService;
use Illuminate\Http\Request;
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
        
        $plan->load(['planType', 'area', 'manager', 'director', 'sections', 'kpis', 'milestones', 'tasks', 'risks']);
        
        return view('plans.show', compact('plan'));
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
