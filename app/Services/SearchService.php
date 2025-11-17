<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\Task;
use App\Models\Risk;
use App\Models\Decision;
use App\Models\Tag;
use Illuminate\Support\Collection;

class SearchService
{
    /**
     * Búsqueda global en todos los modelos
     */
    public function globalSearch(string $query, array $filters = []): array
    {
        $results = [
            'plans' => $this->searchPlans($query, $filters),
            'tasks' => $this->searchTasks($query, $filters),
            'risks' => $this->searchRisks($query, $filters),
            'decisions' => $this->searchDecisions($query, $filters),
        ];

        return $results;
    }

    /**
     * Buscar planes
     */
    public function searchPlans(string $query, array $filters = []): Collection
    {
        $searchQuery = Plan::query()
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            });

        // Filtros
        if (isset($filters['status'])) {
            $searchQuery->where('status', $filters['status']);
        }

        if (isset($filters['area_id'])) {
            $searchQuery->where('area_id', $filters['area_id']);
        }

        if (isset($filters['tags']) && is_array($filters['tags'])) {
            $searchQuery->whereHas('tags', function ($q) use ($filters) {
                $q->whereIn('tags.id', $filters['tags']);
            });
        }

        return $searchQuery->with(['planType', 'area', 'tags'])->limit(20)->get();
    }

    /**
     * Buscar tareas
     */
    public function searchTasks(string $query, array $filters = []): Collection
    {
        $searchQuery = Task::query()
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            });

        // Filtros
        if (isset($filters['status'])) {
            $searchQuery->where('status', $filters['status']);
        }

        if (isset($filters['plan_id'])) {
            $searchQuery->where('plan_id', $filters['plan_id']);
        }

        if (isset($filters['tags']) && is_array($filters['tags'])) {
            $searchQuery->whereHas('tags', function ($q) use ($filters) {
                $q->whereIn('tags.id', $filters['tags']);
            });
        }

        return $searchQuery->with(['plan', 'assignedUser', 'tags'])->limit(20)->get();
    }

    /**
     * Buscar riesgos
     */
    public function searchRisks(string $query, array $filters = []): Collection
    {
        $searchQuery = Risk::query()
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            });

        // Filtros
        if (isset($filters['category'])) {
            $searchQuery->where('category', $filters['category']);
        }

        if (isset($filters['plan_id'])) {
            $searchQuery->where('plan_id', $filters['plan_id']);
        }

        if (isset($filters['tags']) && is_array($filters['tags'])) {
            $searchQuery->whereHas('tags', function ($q) use ($filters) {
                $q->whereIn('tags.id', $filters['tags']);
            });
        }

        return $searchQuery->with(['plan', 'owner', 'tags'])->limit(20)->get();
    }

    /**
     * Buscar decisiones
     */
    public function searchDecisions(string $query, array $filters = []): Collection
    {
        $searchQuery = Decision::query()
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            });

        // Filtros
        if (isset($filters['status'])) {
            $searchQuery->where('status', $filters['status']);
        }

        if (isset($filters['tags']) && is_array($filters['tags'])) {
            $searchQuery->whereHas('tags', function ($q) use ($filters) {
                $q->whereIn('tags.id', $filters['tags']);
            });
        }

        return $searchQuery->with(['proponent', 'tags'])->limit(20)->get();
    }

    /**
     * Buscar por tags
     */
    public function searchByTags(array $tagIds, ?string $type = null): array
    {
        $results = [];

        if (!$type || $type === 'plans') {
            $results['plans'] = Plan::whereHas('tags', function ($q) use ($tagIds) {
                $q->whereIn('tags.id', $tagIds);
            })->with(['planType', 'area', 'tags'])->limit(20)->get();
        }

        if (!$type || $type === 'tasks') {
            $results['tasks'] = Task::whereHas('tags', function ($q) use ($tagIds) {
                $q->whereIn('tags.id', $tagIds);
            })->with(['plan', 'assignedUser', 'tags'])->limit(20)->get();
        }

        if (!$type || $type === 'risks') {
            $results['risks'] = Risk::whereHas('tags', function ($q) use ($tagIds) {
                $q->whereIn('tags.id', $tagIds);
            })->with(['plan', 'owner', 'tags'])->limit(20)->get();
        }

        if (!$type || $type === 'decisions') {
            $results['decisions'] = Decision::whereHas('tags', function ($q) use ($tagIds) {
                $q->whereIn('tags.id', $tagIds);
            })->with(['proponent', 'tags'])->limit(20)->get();
        }

        return $results;
    }
}
