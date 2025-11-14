<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\PlanType;
use App\Models\Area;
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
        $user = auth()->user();
        
        $query = Plan::with(['planType', 'area', 'manager', 'director']);
        
        // Filtrar según rol
        if ($user->isManager()) {
            $query->where('manager_id', $user->id);
        } elseif ($user->isTecnico()) {
            // Los técnicos solo ven planes donde participan
            // TODO: Implementar lógica de participación
        }
        
        $plans = $query->latest()->paginate(15);
        
        return view('plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $planTypes = PlanType::where('is_active', true)->orderBy('order')->get();
        $areas = Area::where('is_active', true)->orderBy('order')->get();
        
        return view('plans.create', compact('planTypes', 'areas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
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

        return redirect()->route('plans.show', $plan)
            ->with('success', 'Plan creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Plan $plan): View
    {
        $plan->load(['planType', 'area', 'manager', 'director', 'sections', 'kpis', 'milestones', 'tasks', 'risks']);
        
        return view('plans.show', compact('plan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Plan $plan): View
    {
        $planTypes = PlanType::where('is_active', true)->orderBy('order')->get();
        $areas = Area::where('is_active', true)->orderBy('order')->get();
        
        return view('plans.edit', compact('plan', 'planTypes', 'areas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plan $plan): RedirectResponse
    {
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
        // TODO: Implementar eliminación
        $plan->delete();
        
        return redirect()->route('plans.index')
            ->with('success', 'Plan eliminado correctamente');
    }
}
