<?php

namespace App\Livewire\Scenarios;

use App\Models\Scenario;
use App\Services\ScenarioSimulationService;
use Livewire\Component;

class ScenarioResults extends Component
{
    public Scenario $scenario;
    public $results = null;

    public function mount(Scenario $scenario)
    {
        $this->scenario = $scenario;
        $this->loadResults();
    }

    public function loadResults()
    {
        $simulationService = app(ScenarioSimulationService::class);
        
        // Recalcular si no hay resultados o si se solicita
        if (!$this->scenario->results) {
            $this->results = $simulationService->calculateImpact($this->scenario);
            $this->scenario->update(['results' => $this->results]);
            $this->scenario->refresh();
        } else {
            $this->results = $this->scenario->results;
        }
    }

    public function recalculate()
    {
        $simulationService = app(ScenarioSimulationService::class);
        $this->results = $simulationService->calculateImpact($this->scenario);
        $this->scenario->update(['results' => $this->results]);
        $this->scenario->refresh();
        session()->flash('success', 'Resultados recalculados correctamente.');
    }

    public function render()
    {
        return view('livewire.scenarios.scenario-results');
    }
}
