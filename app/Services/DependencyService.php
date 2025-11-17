<?php

namespace App\Services;

use App\Models\Milestone;
use App\Models\MilestoneDependency;

class DependencyService
{
    /**
     * Crear una dependencia entre milestones
     */
    public function createDependency(
        Milestone $predecessor,
        Milestone $successor,
        string $type = 'fs',
        int $lagDays = 0
    ): MilestoneDependency {
        // Validar que no se cree una dependencia circular
        if ($this->wouldCreateCycle($predecessor, $successor)) {
            throw new \Exception('No se puede crear una dependencia circular');
        }
        
        return MilestoneDependency::create([
            'predecessor_id' => $predecessor->id,
            'successor_id' => $successor->id,
            'type' => $type,
            'lag_days' => $lagDays,
        ]);
    }

    /**
     * Verificar si crear una dependencia causaría un ciclo
     */
    protected function wouldCreateCycle(Milestone $predecessor, Milestone $successor): bool
    {
        // Si el sucesor ya es predecesor del predecesor (directa o indirectamente)
        return $this->isReachable($successor, $predecessor);
    }

    /**
     * Verificar si un milestone es alcanzable desde otro
     */
    protected function isReachable(Milestone $from, Milestone $to): bool
    {
        $visited = [];
        return $this->dfs($from, $to, $visited);
    }

    /**
     * Búsqueda en profundidad para verificar alcanzabilidad
     */
    protected function dfs(Milestone $current, Milestone $target, &$visited): bool
    {
        if ($current->id === $target->id) {
            return true;
        }
        
        if (isset($visited[$current->id])) {
            return false;
        }
        
        $visited[$current->id] = true;
        
        $successors = Milestone::whereHas('predecessorDependencies', function($q) use ($current) {
            $q->where('predecessor_id', $current->id);
        })->get();
        
        foreach ($successors as $successor) {
            if ($this->dfs($successor, $target, $visited)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Eliminar una dependencia
     */
    public function removeDependency(MilestoneDependency $dependency): bool
    {
        return $dependency->delete();
    }

    /**
     * Obtener todas las dependencias de un milestone
     */
    public function getMilestoneDependencies(Milestone $milestone): array
    {
        return [
            'predecessors' => $milestone->predecessorDependencies->load('predecessor'),
            'successors' => $milestone->successorDependencies->load('successor'),
        ];
    }
}



