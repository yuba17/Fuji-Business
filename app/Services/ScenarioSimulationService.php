<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\Scenario;
use App\Models\Kpi;
use App\Models\Task;
use App\Models\Milestone;
use Illuminate\Support\Collection;

class ScenarioSimulationService
{
    /**
     * Simular cambio de presupuesto
     */
    public function simulateBudgetChange(Plan $plan, float $budgetChangePercent, array $options = []): array
    {
        $originalBudget = $plan->metadata['budget'] ?? 0;
        $newBudget = $originalBudget * (1 + ($budgetChangePercent / 100));

        $impact = [
            'budget_change' => $budgetChangePercent,
            'original_budget' => $originalBudget,
            'new_budget' => $newBudget,
            'budget_difference' => $newBudget - $originalBudget,
            'affected_tasks' => 0,
            'affected_milestones' => 0,
            'kpi_impacts' => [],
            'risk_changes' => [],
        ];

        // Impacto en tareas (si hay reducción de presupuesto, puede afectar recursos)
        if ($budgetChangePercent < 0) {
            $tasks = $plan->tasks()->where('status', '!=', 'done')->get();
            $impact['affected_tasks'] = $tasks->count();
            
            // Simular posibles retrasos
            $estimatedDelayDays = abs($budgetChangePercent) * 0.1; // 0.1 días por cada 1% de reducción
            $impact['estimated_delay_days'] = round($estimatedDelayDays, 1);
        }

        // Impacto en KPIs relacionados con presupuesto
        $budgetKpis = $plan->kpis()->where('name', 'like', '%presupuesto%')
            ->orWhere('name', 'like', '%budget%')
            ->orWhere('name', 'like', '%coste%')
            ->get();

        foreach ($budgetKpis as $kpi) {
            $newValue = $kpi->current_value * (1 + ($budgetChangePercent / 100));
            $impact['kpi_impacts'][] = [
                'kpi_id' => $kpi->id,
                'kpi_name' => $kpi->name,
                'original_value' => $kpi->current_value,
                'new_value' => $newValue,
                'change_percent' => $budgetChangePercent,
            ];
        }

        return $impact;
    }

    /**
     * Simular cambio en el equipo
     */
    public function simulateTeamChange(Plan $plan, int $teamSizeChange, array $options = []): array
    {
        $currentTeamSize = $plan->metadata['team_size'] ?? 1;
        $newTeamSize = max(1, $currentTeamSize + $teamSizeChange);
        $teamChangePercent = (($newTeamSize - $currentTeamSize) / $currentTeamSize) * 100;

        $impact = [
            'team_size_change' => $teamSizeChange,
            'original_team_size' => $currentTeamSize,
            'new_team_size' => $newTeamSize,
            'team_change_percent' => round($teamChangePercent, 2),
            'affected_tasks' => 0,
            'estimated_completion_change' => 0,
            'workload_impact' => [],
        ];

        // Calcular impacto en tiempo de finalización
        if ($teamSizeChange > 0) {
            // Más personas = menos tiempo (hasta cierto punto)
            $efficiencyGain = min(50, $teamChangePercent * 0.8); // Máximo 50% de mejora
            $impact['estimated_completion_change'] = -round($efficiencyGain, 1); // Negativo = reducción de tiempo
        } else {
            // Menos personas = más tiempo
            $efficiencyLoss = abs($teamChangePercent) * 1.2; // Pérdida de eficiencia
            $impact['estimated_completion_change'] = round($efficiencyLoss, 1); // Positivo = aumento de tiempo
        }

        // Impacto en carga de trabajo
        $tasks = $plan->tasks()->where('status', '!=', 'done')->get();
        $totalEstimatedHours = $tasks->sum('estimated_hours');
        
        if ($newTeamSize > 0) {
            $hoursPerPerson = $totalEstimatedHours / $newTeamSize;
            $impact['workload_impact'] = [
                'total_hours' => $totalEstimatedHours,
                'hours_per_person' => round($hoursPerPerson, 2),
                'workload_status' => $hoursPerPerson > 160 ? 'sobrecarga' : ($hoursPerPerson > 120 ? 'alta' : 'normal'),
            ];
        }

        $impact['affected_tasks'] = $tasks->count();

        return $impact;
    }

    /**
     * Simular retraso en el plan
     */
    public function simulateDelay(Plan $plan, int $delayDays, array $options = []): array
    {
        $impact = [
            'delay_days' => $delayDays,
            'original_end_date' => $plan->end_date?->format('Y-m-d'),
            'new_end_date' => $plan->end_date?->addDays($delayDays)->format('Y-m-d'),
            'affected_milestones' => 0,
            'affected_tasks' => 0,
            'kpi_impacts' => [],
            'risk_increases' => [],
        ];

        // Impacto en milestones
        $milestones = $plan->milestones()->where('status', '!=', 'completed')->get();
        $impact['affected_milestones'] = $milestones->count();

        // Impacto en tareas
        $tasks = $plan->tasks()->where('status', '!=', 'done')
            ->where('due_date', '>', now())
            ->get();
        $impact['affected_tasks'] = $tasks->count();

        // Impacto en KPIs relacionados con tiempo
        $timeKpis = $plan->kpis()->where('name', 'like', '%tiempo%')
            ->orWhere('name', 'like', '%time%')
            ->orWhere('name', 'like', '%fecha%')
            ->orWhere('name', 'like', '%deadline%')
            ->get();

        foreach ($timeKpis as $kpi) {
            $impact['kpi_impacts'][] = [
                'kpi_id' => $kpi->id,
                'kpi_name' => $kpi->name,
                'original_value' => $kpi->current_value,
                'impact' => 'Retraso de ' . $delayDays . ' días',
            ];
        }

        // Aumento de riesgos por retraso
        $risks = $plan->risks()->where('status', '!=', 'mitigated')->get();
        foreach ($risks as $risk) {
            $newProbability = min(10, $risk->probability + 1); // Aumentar probabilidad en 1
            $impact['risk_increases'][] = [
                'risk_id' => $risk->id,
                'risk_name' => $risk->name,
                'original_probability' => $risk->probability,
                'new_probability' => $newProbability,
                'original_risk_level' => $risk->risk_level,
                'new_risk_level' => $newProbability * $risk->impact,
            ];
        }

        return $impact;
    }

    /**
     * Calcular impacto general de un escenario
     */
    public function calculateImpact(Scenario $scenario): array
    {
        $plan = $scenario->plan;
        $params = $scenario->simulation_params ?? [];

        $totalImpact = [
            'scenario_id' => $scenario->id,
            'scenario_name' => $scenario->name,
            'overall_impact' => 'neutral',
            'impact_score' => 0,
            'budget_impact' => null,
            'time_impact' => null,
            'team_impact' => null,
            'risk_impact' => null,
            'kpi_impacts' => [],
            'recommendations' => [],
        ];

        // Simular cambios de presupuesto
        if (isset($params['budget_change'])) {
            $totalImpact['budget_impact'] = $this->simulateBudgetChange(
                $plan,
                $params['budget_change'],
                $params['budget_options'] ?? []
            );
            $totalImpact['impact_score'] += $params['budget_change'] > 0 ? -5 : 5;
        }

        // Simular cambios de equipo
        if (isset($params['team_change'])) {
            $totalImpact['team_impact'] = $this->simulateTeamChange(
                $plan,
                $params['team_change'],
                $params['team_options'] ?? []
            );
            $totalImpact['impact_score'] += $params['team_change'] > 0 ? -3 : 3;
        }

        // Simular retrasos
        if (isset($params['delay_days'])) {
            $totalImpact['time_impact'] = $this->simulateDelay(
                $plan,
                $params['delay_days'],
                $params['delay_options'] ?? []
            );
            $totalImpact['impact_score'] += $params['delay_days'] * 0.5;
        }

        // Determinar impacto general
        if ($totalImpact['impact_score'] < -5) {
            $totalImpact['overall_impact'] = 'positive';
        } elseif ($totalImpact['impact_score'] > 5) {
            $totalImpact['overall_impact'] = 'negative';
        } else {
            $totalImpact['overall_impact'] = 'neutral';
        }

        // Generar recomendaciones
        if ($totalImpact['overall_impact'] === 'negative') {
            $totalImpact['recommendations'][] = 'Considerar ajustar los parámetros del escenario para reducir el impacto negativo.';
        }
        if (isset($totalImpact['budget_impact']['estimated_delay_days']) && $totalImpact['budget_impact']['estimated_delay_days'] > 5) {
            $totalImpact['recommendations'][] = 'La reducción de presupuesto puede causar retrasos significativos.';
        }
        if (isset($totalImpact['team_impact']['workload_impact']['workload_status']) && $totalImpact['team_impact']['workload_impact']['workload_status'] === 'sobrecarga') {
            $totalImpact['recommendations'][] = 'El equipo está sobrecargado. Considerar redistribuir tareas o aumentar el equipo.';
        }

        return $totalImpact;
    }

    /**
     * Comparar dos escenarios
     */
    public function compareScenarios(Scenario $scenario1, Scenario $scenario2): array
    {
        $impact1 = $this->calculateImpact($scenario1);
        $impact2 = $this->calculateImpact($scenario2);

        return [
            'scenario1' => $impact1,
            'scenario2' => $impact2,
            'comparison' => [
                'budget_difference' => ($impact2['budget_impact']['budget_difference'] ?? 0) - ($impact1['budget_impact']['budget_difference'] ?? 0),
                'time_difference' => ($impact2['time_impact']['delay_days'] ?? 0) - ($impact1['time_impact']['delay_days'] ?? 0),
                'team_difference' => ($impact2['team_impact']['team_size_change'] ?? 0) - ($impact1['team_impact']['team_size_change'] ?? 0),
                'impact_score_difference' => $impact2['impact_score'] - $impact1['impact_score'],
                'better_scenario' => $impact1['impact_score'] < $impact2['impact_score'] ? 'scenario1' : 'scenario2',
            ],
        ];
    }
}
