<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Scenario;
use App\Services\ScenarioSimulationService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ScenarioController extends Controller
{
    public function __construct(
        protected ScenarioSimulationService $simulationService
    ) {
        $this->middleware('auth');
    }

    /**
     * Listar escenarios de un plan
     */
    public function index(Plan $plan): View
    {
        $this->authorize('view', $plan);

        $scenarios = $plan->scenarios()
            ->with('creator')
            ->latest()
            ->paginate(10);

        return view('scenarios.index', compact('plan', 'scenarios'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create(Plan $plan): View
    {
        $this->authorize('update', $plan);

        return view('scenarios.create', compact('plan'));
    }

    /**
     * Guardar nuevo escenario
     */
    public function store(Request $request, Plan $plan): RedirectResponse
    {
        $this->authorize('update', $plan);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'simulation_params' => 'required|array',
        ]);

        $scenario = Scenario::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'plan_id' => $plan->id,
            'area_id' => $plan->area_id,
            'created_by' => auth()->id(),
            'simulation_params' => $validated['simulation_params'],
        ]);

        // Calcular resultados
        $results = $this->simulationService->calculateImpact($scenario);
        $scenario->update(['results' => $results]);

        return redirect()->route('scenarios.show', [$plan, $scenario])
            ->with('success', 'Escenario creado y simulado correctamente.');
    }

    /**
     * Mostrar escenario
     */
    public function show(Plan $plan, Scenario $scenario): View
    {
        $this->authorize('view', $plan);

        if ($scenario->plan_id !== $plan->id) {
            abort(404);
        }

        // Recalcular si no hay resultados
        if (!$scenario->results) {
            $results = $this->simulationService->calculateImpact($scenario);
            $scenario->update(['results' => $results]);
            $scenario->refresh();
        }

        return view('scenarios.show', compact('plan', 'scenario'));
    }

    /**
     * Comparar escenarios
     */
    public function compare(Request $request, Plan $plan): View
    {
        $this->authorize('view', $plan);

        $scenario1Id = $request->get('scenario1');
        $scenario2Id = $request->get('scenario2');

        $scenario1 = Scenario::where('plan_id', $plan->id)->findOrFail($scenario1Id);
        $scenario2 = Scenario::where('plan_id', $plan->id)->findOrFail($scenario2Id);

        $comparison = $this->simulationService->compareScenarios($scenario1, $scenario2);

        return view('scenarios.compare', compact('plan', 'scenario1', 'scenario2', 'comparison'));
    }

    /**
     * Aplicar escenario al plan
     */
    public function apply(Plan $plan, Scenario $scenario): RedirectResponse
    {
        $this->authorize('update', $plan);

        if ($scenario->plan_id !== $plan->id) {
            abort(404);
        }

        // Marcar escenario como aplicado
        $scenario->update(['is_applied' => true]);

        // Aquí se podría implementar la lógica para aplicar los cambios del escenario al plan
        // Por ahora solo marcamos el escenario como aplicado

        return redirect()->route('plans.show', $plan)
            ->with('success', 'Escenario aplicado al plan.');
    }

    /**
     * Eliminar escenario
     */
    public function destroy(Plan $plan, Scenario $scenario): RedirectResponse
    {
        $this->authorize('update', $plan);

        if ($scenario->plan_id !== $plan->id) {
            abort(404);
        }

        $scenario->delete();

        return redirect()->route('scenarios.index', $plan)
            ->with('success', 'Escenario eliminado correctamente.');
    }
}
