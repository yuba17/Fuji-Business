<?php

namespace App\Http\Controllers;

use App\Models\Kpi;
use App\Models\Plan;
use App\Models\Area;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class KpiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        
        $query = Kpi::with(['plan', 'area', 'responsible'])
            ->where('is_active', true);
        
        // Filtrar según rol
        if ($user->isManager()) {
            $query->whereHas('plan', function($q) use ($user) {
                $q->where('manager_id', $user->id);
            });
        }
        
        // Filtros
        if ($request->filled('plan_id')) {
            $query->where('plan_id', $request->plan_id);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $kpis = $query->latest()->paginate(15);
        $plans = Plan::where('status', '!=', 'archived')->get();
        
        return view('kpis.index', compact('kpis', 'plans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $plans = Plan::where('status', '!=', 'archived')->get();
        $areas = Area::where('is_active', true)->get();
        $users = User::all();
        $planId = $request->get('plan_id');
        
        return view('kpis.create', compact('plans', 'areas', 'users', 'planId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'plan_id' => 'required|exists:plans,id',
            'area_id' => 'nullable|exists:areas,id',
            'type' => 'nullable|string',
            'unit' => 'nullable|string|max:50',
            'target_value' => 'required|numeric',
            'current_value' => 'nullable|numeric',
            'calculation_method' => 'nullable|string',
            'formula' => 'nullable|string',
            'update_frequency' => 'nullable|string',
            'threshold_green' => 'nullable|numeric|min:0|max:100',
            'threshold_yellow' => 'nullable|numeric|min:0|max:100',
            'responsible_id' => 'nullable|exists:users,id',
            'kpi_type' => 'nullable|string',
        ]);

        $validated['status'] = 'green';
        $validated['is_active'] = true;
        $validated['current_value'] = $validated['current_value'] ?? 0;

        $kpi = Kpi::create($validated);
        $kpi->updateStatus();

        return redirect()->route('kpis.show', $kpi)
            ->with('success', 'KPI creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kpi $kpi): View
    {
        $kpi->load(['plan', 'area', 'responsible', 'history.updater']);
        
        return view('kpis.show', compact('kpi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kpi $kpi): View
    {
        $plans = Plan::where('status', '!=', 'archived')->get();
        $areas = Area::where('is_active', true)->get();
        $users = User::all();
        
        return view('kpis.edit', compact('kpi', 'plans', 'areas', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kpi $kpi): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'plan_id' => 'required|exists:plans,id',
            'area_id' => 'nullable|exists:areas,id',
            'type' => 'nullable|string',
            'unit' => 'nullable|string|max:50',
            'target_value' => 'required|numeric',
            'current_value' => 'nullable|numeric',
            'calculation_method' => 'nullable|string',
            'formula' => 'nullable|string',
            'update_frequency' => 'nullable|string',
            'threshold_green' => 'nullable|numeric|min:0|max:100',
            'threshold_yellow' => 'nullable|numeric|min:0|max:100',
            'responsible_id' => 'nullable|exists:users,id',
            'kpi_type' => 'nullable|string',
        ]);

        $kpi->update($validated);
        $kpi->updateStatus();

        return redirect()->route('kpis.show', $kpi)
            ->with('success', 'KPI actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kpi $kpi): RedirectResponse
    {
        $kpi->update(['is_active' => false]);
        
        return redirect()->route('kpis.index')
            ->with('success', 'KPI desactivado correctamente');
    }
}
