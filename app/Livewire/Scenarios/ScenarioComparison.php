<?php

namespace App\Livewire\Scenarios;

use App\Models\Plan;
use App\Models\Scenario;
use App\Services\ScenarioSimulationService;
use Livewire\Component;

class ScenarioComparison extends Component
{
    public Plan $plan;
    public $scenario1Id = null;
    public $scenario2Id = null;
    public $comparison = null;

    protected $listeners = ['scenariosSelected' => 'compare'];

    public function mount(Plan $plan)
    {
        $this->plan = $plan;
    }

    public function compare()
    {
        if (!$this->scenario1Id || !$this->scenario2Id) {
            return;
        }

        $scenario1 = Scenario::where('plan_id', $this->plan->id)->find($this->scenario1Id);
        $scenario2 = Scenario::where('plan_id', $this->plan->id)->find($this->scenario2Id);

        if (!$scenario1 || !$scenario2) {
            session()->flash('error', 'Uno o ambos escenarios no fueron encontrados.');
            return;
        }

        $simulationService = app(ScenarioSimulationService::class);
        $this->comparison = $simulationService->compareScenarios($scenario1, $scenario2);
    }

    public function render()
    {
        $scenarios = $this->plan->scenarios()->latest()->get();

        return view('livewire.scenarios.scenario-comparison', compact('scenarios'));
    }
}
