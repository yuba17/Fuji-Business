<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\Milestone;
use Carbon\Carbon;

class RoadmapService
{
    /**
     * Calcular la ruta crítica del plan
     */
    public function calculateCriticalPath(Plan $plan): array
    {
        $milestones = $plan->milestones()
            ->with(['predecessorDependencies', 'successorDependencies'])
            ->get();
        
        $criticalPath = [];
        $visited = [];
        
        // Encontrar milestones sin predecesores (inicio)
        $startMilestones = $milestones->filter(function($milestone) {
            return $milestone->predecessorDependencies->isEmpty();
        });
        
        foreach ($startMilestones as $start) {
            $path = $this->findLongestPath($start, $milestones, $visited);
            if (count($path) > count($criticalPath)) {
                $criticalPath = $path;
            }
        }
        
        return $criticalPath;
    }

    /**
     * Encontrar el camino más largo desde un milestone
     */
    protected function findLongestPath(Milestone $milestone, $allMilestones, &$visited): array
    {
        if (isset($visited[$milestone->id])) {
            return [];
        }
        
        $visited[$milestone->id] = true;
        $path = [$milestone];
        $longestSubPath = [];
        
        // Buscar sucesores
        $successors = $allMilestones->filter(function($m) use ($milestone) {
            return $milestone->successorDependencies->contains('successor_id', $m->id);
        });
        
        foreach ($successors as $successor) {
            $subPath = $this->findLongestPath($successor, $allMilestones, $visited);
            if (count($subPath) > count($longestSubPath)) {
                $longestSubPath = $subPath;
            }
        }
        
        return array_merge($path, $longestSubPath);
    }

    /**
     * Verificar retrasos en milestones
     */
    public function checkDelays(Plan $plan): array
    {
        $delayedMilestones = $plan->milestones()
            ->where('status', '!=', 'completed')
            ->where('status', '!=', 'cancelled')
            ->where('target_date', '<', now())
            ->get();
        
        return $delayedMilestones->map(function($milestone) {
            return [
                'milestone' => $milestone,
                'days_delayed' => now()->diffInDays($milestone->target_date),
            ];
        })->toArray();
    }

    /**
     * Calcular el progreso general del plan basado en milestones
     */
    public function calculatePlanProgress(Plan $plan): float
    {
        $milestones = $plan->milestones;
        
        if ($milestones->isEmpty()) {
            return 0;
        }
        
        $totalProgress = $milestones->sum('progress_percentage');
        return $totalProgress / $milestones->count();
    }

    /**
     * Obtener milestones próximos a vencer
     */
    public function getUpcomingMilestones(Plan $plan, int $days = 30): array
    {
        $upcomingDate = now()->addDays($days);
        
        return $plan->milestones()
            ->where('status', '!=', 'completed')
            ->where('status', '!=', 'cancelled')
            ->whereBetween('target_date', [now(), $upcomingDate])
            ->orderBy('target_date')
            ->get()
            ->toArray();
    }
}


