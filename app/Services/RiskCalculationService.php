<?php

namespace App\Services;

use App\Models\Risk;

class RiskCalculationService
{
    /**
     * Obtener riesgos críticos
     */
    public function getCriticalRisks($planId = null, $areaId = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = Risk::where('category', 'critico')
            ->orWhere('category', 'alto');

        if ($planId) {
            $query->where('plan_id', $planId);
        }

        if ($areaId) {
            $query->where('area_id', $areaId);
        }

        return $query->orderBy('risk_level', 'desc')
            ->orderBy('identified_at', 'desc')
            ->get();
    }

    /**
     * Calcular el nivel de riesgo total de un plan
     */
    public function calculatePlanRiskLevel($planId): float
    {
        $risks = Risk::where('plan_id', $planId)->get();
        
        if ($risks->isEmpty()) {
            return 0;
        }
        
        $totalRiskLevel = $risks->sum('risk_level');
        $maxPossibleRisk = $risks->count() * 25; // 5 * 5 = máximo nivel
        
        return ($totalRiskLevel / $maxPossibleRisk) * 100;
    }

    /**
     * Obtener distribución de riesgos por categoría
     */
    public function getRiskDistribution($planId = null): array
    {
        $query = Risk::query();
        
        if ($planId) {
            $query->where('plan_id', $planId);
        }
        
        $risks = $query->get();
        
        return [
            'critico' => $risks->where('category', 'critico')->count(),
            'alto' => $risks->where('category', 'alto')->count(),
            'medio' => $risks->where('category', 'medio')->count(),
            'bajo' => $risks->where('category', 'bajo')->count(),
            'total' => $risks->count(),
        ];
    }

    /**
     * Obtener riesgos por estrategia
     */
    public function getRisksByStrategy($planId = null): array
    {
        $query = Risk::query();
        
        if ($planId) {
            $query->where('plan_id', $planId);
        }
        
        $risks = $query->get();
        
        return [
            'avoid' => $risks->where('strategy', 'avoid')->count(),
            'mitigate' => $risks->where('strategy', 'mitigate')->count(),
            'transfer' => $risks->where('strategy', 'transfer')->count(),
            'accept' => $risks->where('strategy', 'accept')->count(),
            'no_strategy' => $risks->whereNull('strategy')->count(),
        ];
    }

    /**
     * Calcular costo total de mitigación
     */
    public function calculateTotalMitigationCost($planId = null): float
    {
        $query = Risk::query();
        
        if ($planId) {
            $query->where('plan_id', $planId);
        }
        
        return $query->sum('estimated_cost') ?? 0;
    }
}



