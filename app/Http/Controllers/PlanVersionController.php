<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\PlanVersion;
use App\Services\PlanVersionService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PlanVersionController extends Controller
{
    protected $versionService;

    public function __construct(PlanVersionService $versionService)
    {
        $this->versionService = $versionService;
    }

    /**
     * Mostrar historial de versiones de un plan
     */
    public function index(Plan $plan): View
    {
        $this->authorize('view', $plan);
        
        $versions = $plan->versions()
            ->with('creator')
            ->orderBy('version_number', 'desc')
            ->paginate(15);
        
        return view('plans.versions', compact('plan', 'versions'));
    }

    /**
     * Mostrar una versión específica
     */
    public function show(Plan $plan, PlanVersion $version): View
    {
        $this->authorize('view', $plan);
        
        if ($version->plan_id !== $plan->id) {
            abort(404);
        }
        
        $version->load('creator');
        $snapshot = $version->snapshot;
        
        return view('plans.version-show', compact('plan', 'version', 'snapshot'));
    }

    /**
     * Comparar dos versiones
     */
    public function compare(Plan $plan, PlanVersion $version1, PlanVersion $version2 = null): View
    {
        $this->authorize('view', $plan);
        
        if ($version1->plan_id !== $plan->id) {
            abort(404);
        }
        
        // Si no se especifica versión 2, comparar con la versión actual
        if (!$version2) {
            $currentVersion = $this->versionService->createVersion($plan, 'Snapshot para comparación');
            $version2 = $currentVersion;
        } elseif ($version2->plan_id !== $plan->id) {
            abort(404);
        }
        
        $differences = $this->versionService->compareVersions($version1, $version2);
        
        return view('plans.version-compare', compact('plan', 'version1', 'version2', 'differences'));
    }

    /**
     * Restaurar un plan a una versión específica
     */
    public function restore(Plan $plan, PlanVersion $version): RedirectResponse
    {
        $this->authorize('update', $plan);
        
        if ($version->plan_id !== $plan->id) {
            abort(404);
        }
        
        $this->versionService->restoreVersion($version);
        
        return redirect()->route('plans.show', $plan)
            ->with('success', "Plan restaurado a la versión {$version->version_number}");
    }

    /**
     * Crear una nueva versión del plan
     */
    public function store(Request $request, Plan $plan): RedirectResponse
    {
        $this->authorize('update', $plan);
        
        $validated = $request->validate([
            'change_summary' => 'nullable|string|max:1000',
        ]);
        
        $version = $this->versionService->createVersion(
            $plan,
            $validated['change_summary'] ?? null
        );
        
        return redirect()->route('plans.versions', $plan)
            ->with('success', "Versión {$version->version_number} creada correctamente");
    }
}
