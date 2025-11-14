<?php

namespace App\Http\Controllers;

use App\Models\Decision;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DecisionController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Decision::class);
        
        $query = Decision::with(['proponent', 'plans']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('plan_id')) {
            $query->whereHas('plans', function($q) use ($request) {
                $q->where('plans.id', $request->plan_id);
            });
        }
        
        $decisions = $query->latest('decision_date')->paginate(15);
        $plans = Plan::where('status', '!=', 'archived')->get();
        
        return view('decisions.index', compact('decisions', 'plans'));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Decision::class);
        
        $plans = Plan::where('status', '!=', 'archived')->get();
        $users = User::all();
        $planId = $request->get('plan_id');
        
        return view('decisions.create', compact('plans', 'users', 'planId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Decision::class);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'decision_date' => 'nullable|date',
            'impact_type' => 'nullable|in:strategic,operational,financial,technical,organizational',
            'status' => 'nullable|in:proposed,discussion,pending_approval,approved,rejected,implemented,reviewed',
            'alternatives_considered' => 'nullable|string',
            'rationale' => 'nullable|string',
            'expected_impact' => 'nullable|string',
            'plan_ids' => 'nullable|array',
            'plan_ids.*' => 'exists:plans,id',
        ]);

        $validated['proponent_id'] = auth()->id();
        $validated['decision_date'] = $validated['decision_date'] ?? now();
        $validated['status'] = $validated['status'] ?? 'proposed';

        $planIds = $validated['plan_ids'] ?? [];
        unset($validated['plan_ids']);

        $decision = Decision::create($validated);
        
        if (!empty($planIds)) {
            $decision->plans()->attach($planIds);
        }

        return redirect()->route('decisions.show', $decision)
            ->with('success', 'Decisión creada correctamente');
    }

    public function show(Decision $decision): View
    {
        $this->authorize('view', $decision);
        
        $decision->load(['proponent', 'plans']);
        
        return view('decisions.show', compact('decision'));
    }

    public function edit(Decision $decision): View
    {
        $this->authorize('update', $decision);
        
        $plans = Plan::where('status', '!=', 'archived')->get();
        $users = User::all();
        
        return view('decisions.edit', compact('decision', 'plans', 'users'));
    }

    public function update(Request $request, Decision $decision): RedirectResponse
    {
        $this->authorize('update', $decision);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'decision_date' => 'nullable|date',
            'impact_type' => 'nullable|in:strategic,operational,financial,technical,organizational',
            'status' => 'nullable|in:proposed,discussion,pending_approval,approved,rejected,implemented,reviewed',
            'alternatives_considered' => 'nullable|string',
            'rationale' => 'nullable|string',
            'expected_impact' => 'nullable|string',
            'plan_ids' => 'nullable|array',
            'plan_ids.*' => 'exists:plans,id',
        ]);

        $planIds = $validated['plan_ids'] ?? [];
        unset($validated['plan_ids']);

        $decision->update($validated);
        
        if (isset($planIds)) {
            $decision->plans()->sync($planIds);
        }

        return redirect()->route('decisions.show', $decision)
            ->with('success', 'Decisión actualizada correctamente');
    }

    public function destroy(Decision $decision): RedirectResponse
    {
        $this->authorize('delete', $decision);
        
        $decision->delete();
        
        return redirect()->route('decisions.index')
            ->with('success', 'Decisión eliminada correctamente');
    }
}
