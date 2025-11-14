<?php

namespace App\Http\Controllers;

use App\Models\Risk;
use App\Models\Plan;
use App\Models\Area;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RiskController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        
        $query = Risk::with(['plan', 'area', 'owner']);
        
        if ($user->isManager()) {
            $query->whereHas('plan', function($q) use ($user) {
                $q->where('manager_id', $user->id);
            });
        }
        
        if ($request->filled('plan_id')) {
            $query->where('plan_id', $request->plan_id);
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        $risks = $query->latest()->paginate(15);
        $plans = Plan::where('status', '!=', 'archived')->get();
        
        return view('risks.index', compact('risks', 'plans'));
    }

    public function create(Request $request): View
    {
        $plans = Plan::where('status', '!=', 'archived')->get();
        $areas = Area::where('is_active', true)->get();
        $users = User::all();
        $planId = $request->get('plan_id');
        
        return view('risks.create', compact('plans', 'areas', 'users', 'planId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'plan_id' => 'required|exists:plans,id',
            'area_id' => 'nullable|exists:areas,id',
            'probability' => 'required|integer|min:1|max:5',
            'impact' => 'required|integer|min:1|max:5',
            'strategy' => 'nullable|in:avoid,mitigate,transfer,accept',
            'owner_id' => 'nullable|exists:users,id',
            'status' => 'nullable|in:identified,assessed,mitigated,monitored,closed',
        ]);

        $validated['status'] = $validated['status'] ?? 'identified';

        $risk = Risk::create($validated);

        return redirect()->route('risks.show', $risk)
            ->with('success', 'Riesgo creado correctamente');
    }

    public function show(Risk $risk): View
    {
        $risk->load(['plan', 'area', 'owner', 'mitigationActions.responsible']);
        
        return view('risks.show', compact('risk'));
    }

    public function edit(Risk $risk): View
    {
        $plans = Plan::where('status', '!=', 'archived')->get();
        $areas = Area::where('is_active', true)->get();
        $users = User::all();
        
        return view('risks.edit', compact('risk', 'plans', 'areas', 'users'));
    }

    public function update(Request $request, Risk $risk): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'plan_id' => 'required|exists:plans,id',
            'area_id' => 'nullable|exists:areas,id',
            'probability' => 'required|integer|min:1|max:5',
            'impact' => 'required|integer|min:1|max:5',
            'strategy' => 'nullable|in:avoid,mitigate,transfer,accept',
            'owner_id' => 'nullable|exists:users,id',
            'status' => 'nullable|in:identified,assessed,mitigated,monitored,closed',
        ]);

        $risk->update($validated);

        return redirect()->route('risks.show', $risk)
            ->with('success', 'Riesgo actualizado correctamente');
    }

    public function destroy(Risk $risk): RedirectResponse
    {
        $risk->delete();
        
        return redirect()->route('risks.index')
            ->with('success', 'Riesgo eliminado correctamente');
    }
}
