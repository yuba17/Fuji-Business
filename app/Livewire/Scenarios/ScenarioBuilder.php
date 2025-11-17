<?php

namespace App\Livewire\Scenarios;

use App\Models\Plan;
use App\Models\Scenario;
use Livewire\Component;

class ScenarioBuilder extends Component
{
    public Plan $plan;
    public $name = '';
    public $description = '';
    public $simulationParams = [
        'budget_change' => null,
        'team_change' => null,
        'delay_days' => null,
    ];

    public function mount(Plan $plan)
    {
        $this->plan = $plan;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'simulationParams.budget_change' => 'nullable|numeric',
            'simulationParams.team_change' => 'nullable|integer',
            'simulationParams.delay_days' => 'nullable|integer|min:0',
        ]);

        // Filtrar parámetros nulos
        $params = array_filter($this->simulationParams, fn($value) => $value !== null && $value !== '');

        if (empty($params)) {
            session()->flash('error', 'Debes especificar al menos un parámetro de simulación.');
            return;
        }

        $scenario = Scenario::create([
            'name' => $this->name,
            'description' => $this->description,
            'plan_id' => $this->plan->id,
            'area_id' => $this->plan->area_id,
            'created_by' => auth()->id(),
            'simulation_params' => $params,
        ]);

        // Calcular resultados
        $simulationService = app(\App\Services\ScenarioSimulationService::class);
        $results = $simulationService->calculateImpact($scenario);
        $scenario->update(['results' => $results]);

        session()->flash('success', 'Escenario creado y simulado correctamente.');
        return redirect()->route('scenarios.show', [$this->plan, $scenario]);
    }

    public function render()
    {
        return view('livewire.scenarios.scenario-builder');
    }
}
