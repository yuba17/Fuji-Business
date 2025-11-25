<?php

namespace App\Http\Controllers;

use App\Models\Kpi;
use App\Models\Plan;
use App\Models\Area;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class KpiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Kpi::class);
        
        $user = auth()->user();
        
        $query = Kpi::with(['plan', 'area', 'responsible'])
            ->where('is_active', true);
        
        // Filtrar según rol
        if ($user->isManager()) {
            // Optimización: Usar pluck directo en lugar de cargar relación completa
            $userAreaIds = $user->areas()->pluck('areas.id')->toArray();
            
            $query->where(function($q) use ($user, $userAreaIds) {
                $q->where('responsible_id', $user->id);
                
                if (!empty($userAreaIds)) {
                    $q->orWhereIn('area_id', $userAreaIds);
                }
                
                // Revertir a whereHas: puede ser más rápido con índices adecuados y Eloquent lo optimiza
                $q->orWhereHas('plan', function($planQ) use ($user) {
                    $planQ->where('manager_id', $user->id)
                          ->orWhere('director_id', $user->id);
                });
            });
        } elseif ($user->isTecnico()) {
            $query->where('responsible_id', $user->id);
        } elseif ($user->isVisualizacion()) {
            // Visualización ve todos los KPIs
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
        $this->authorize('create', Kpi::class);
        
        $plans = Plan::where('status', '!=', 'archived')->get();
        $areas = Area::where('is_active', true)->get();
        // Optimización: Solo seleccionar campos necesarios y ordenar por nombre
        $users = User::select('id', 'name')->orderBy('name')->get();
        $planId = $request->get('plan_id');
        
        return view('kpis.create', compact('plans', 'areas', 'users', 'planId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Kpi::class);
        
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
        $this->authorize('view', $kpi);
        
        $kpi->load(['plan', 'area', 'responsible', 'history.updater']);
        
        return view('kpis.show', compact('kpi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kpi $kpi): View
    {
        $this->authorize('update', $kpi);
        
        $plans = Plan::where('status', '!=', 'archived')->get();
        $areas = Area::where('is_active', true)->get();
        // Optimización: Solo seleccionar campos necesarios y ordenar por nombre
        $users = User::select('id', 'name')->orderBy('name')->get();
        
        return view('kpis.edit', compact('kpi', 'plans', 'areas', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kpi $kpi): RedirectResponse
    {
        $this->authorize('update', $kpi);
        
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
        $this->authorize('delete', $kpi);
        
        $kpi->update(['is_active' => false]);
        
        return redirect()->route('kpis.index')
            ->with('success', 'KPI desactivado correctamente');
    }
}
