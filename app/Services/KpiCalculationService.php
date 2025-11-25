<?php

namespace App\Services;

use App\Models\Kpi;

class KpiCalculationService
{
    /**
     * Calcular el porcentaje de cumplimiento de un KPI
     */
    public function calculatePercentage(Kpi $kpi): float
    {
        if ($kpi->target_value == 0) {
            return 0;
        }

        return ($kpi->current_value / $kpi->target_value) * 100;
    }

    /**
     * Actualizar el estado del KPI basado en umbrales
     */
    public function updateStatus(Kpi $kpi): void
    {
        $percentage = $this->calculatePercentage($kpi);
        
        // Optimización: Eliminado check innecesario (calculatePercentage siempre devuelve float)
        $green = $kpi->threshold_green ?? 80;
        $yellow = $kpi->threshold_yellow ?? 60;

        if ($percentage >= $green) {
            $status = 'green';
        } elseif ($percentage >= $yellow) {
            $status = 'yellow';
        } else {
            $status = 'red';
        }

        $kpi->update(['status' => $status]);
    }

    /**
     * Calcular KPI usando fórmula personalizada
     */
    public function calculateWithFormula(Kpi $kpi, array $variables = []): float
    {
        if (empty($kpi->formula)) {
            return $kpi->current_value;
        }

        // Reemplazar variables en la fórmula
        $formula = $kpi->formula;
        foreach ($variables as $key => $value) {
            $formula = str_replace('{' . $key . '}', $value, $formula);
        }

        // Evaluar fórmula de forma segura
        try {
            // Solo permitir operaciones matemáticas básicas
            $formula = preg_replace('/[^0-9+\-*/().\s]/', '', $formula);
            $result = eval("return $formula;");
            return (float) $result;
        } catch (\Exception $e) {
            return $kpi->current_value;
        }
    }

    /**
     * Obtener KPIs críticos (rojos o amarillos)
     */
    public function getCriticalKpis($planId = null, $areaId = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = Kpi::where('is_active', true)
            ->whereIn('status', ['red', 'yellow']);

        if ($planId) {
            $query->where('plan_id', $planId);
        }

        if ($areaId) {
            $query->where('area_id', $areaId);
        }

        return $query->orderBy('status', 'desc')
            ->orderBy('last_updated_at', 'desc')
            ->get();
    }

    /**
     * Verificar si un KPI necesita actualización
     */
    public function needsUpdate(Kpi $kpi): bool
    {
        if (!$kpi->last_updated_at) {
            return true;
        }

        $frequency = $kpi->update_frequency ?? 'monthly';
        
        return match($frequency) {
            'daily' => $kpi->last_updated_at->lt(now()->subDay()),
            'weekly' => $kpi->last_updated_at->lt(now()->subWeek()),
            'monthly' => $kpi->last_updated_at->lt(now()->subMonth()),
            'quarterly' => $kpi->last_updated_at->lt(now()->subMonths(3)),
            'yearly' => $kpi->last_updated_at->lt(now()->subYear()),
            default => false,
        };
    }
}

