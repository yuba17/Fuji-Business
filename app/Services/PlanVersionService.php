<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\PlanVersion;
use Illuminate\Support\Facades\DB;

class PlanVersionService
{
    /**
     * Crear una nueva versión del plan
     */
    public function createVersion(Plan $plan, string $changeSummary = null, int $userId = null): PlanVersion
    {
        $userId = $userId ?? auth()->id();
        
        // Obtener el siguiente número de versión
        $nextVersion = $plan->versions()->max('version_number') + 1;
        
        // Crear snapshot completo del plan
        $snapshot = $this->createSnapshot($plan);
        
        // Crear la versión
        $version = PlanVersion::create([
            'plan_id' => $plan->id,
            'version_number' => $nextVersion,
            'snapshot' => $snapshot,
            'created_by' => $userId,
            'change_summary' => $changeSummary,
        ]);
        
        // Actualizar el número de versión del plan
        $plan->update(['version' => $nextVersion]);
        
        return $version;
    }
    
    /**
     * Crear un snapshot completo del plan
     */
    protected function createSnapshot(Plan $plan): array
    {
        $plan->load([
            'sections',
            'kpis',
            'milestones',
            'tasks',
            'risks',
            'decisions',
            'clients',
            'projects',
        ]);
        
        return [
            'plan' => [
                'id' => $plan->id,
                'name' => $plan->name,
                'slug' => $plan->slug,
                'description' => $plan->description,
                'plan_type_id' => $plan->plan_type_id,
                'area_id' => $plan->area_id,
                'manager_id' => $plan->manager_id,
                'director_id' => $plan->director_id,
                'parent_plan_id' => $plan->parent_plan_id,
                'status' => $plan->status,
                'start_date' => $plan->start_date?->toDateString(),
                'target_date' => $plan->target_date?->toDateString(),
                'review_date' => $plan->review_date?->toDateString(),
                'end_date' => $plan->end_date?->toDateString(),
                'version' => $plan->version,
                'metadata' => $plan->metadata,
            ],
            'sections' => $plan->sections->map(function ($section) {
                return [
                    'id' => $section->id,
                    'title' => $section->title,
                    'slug' => $section->slug,
                    'content' => $section->content,
                    'type' => $section->type,
                    'order' => $section->order,
                    'is_required' => $section->is_required,
                    'metadata' => $section->metadata,
                ];
            })->toArray(),
            'kpis' => $plan->kpis->map(function ($kpi) {
                return [
                    'id' => $kpi->id,
                    'name' => $kpi->name,
                    'description' => $kpi->description,
                    'type' => $kpi->type,
                    'unit' => $kpi->unit,
                    'target_value' => $kpi->target_value,
                    'current_value' => $kpi->current_value,
                    'status' => $kpi->status,
                ];
            })->toArray(),
            'milestones' => $plan->milestones->map(function ($milestone) {
                return [
                    'id' => $milestone->id,
                    'name' => $milestone->name,
                    'description' => $milestone->description,
                    'start_date' => $milestone->start_date?->toDateString(),
                    'end_date' => $milestone->end_date?->toDateString(),
                    'target_date' => $milestone->target_date?->toDateString(),
                    'status' => $milestone->status,
                    'progress_percentage' => $milestone->progress_percentage,
                ];
            })->toArray(),
            'tasks' => $plan->tasks->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'status' => $task->status,
                    'priority' => $task->priority,
                    'due_date' => $task->due_date?->toDateString(),
                ];
            })->toArray(),
            'risks' => $plan->risks->map(function ($risk) {
                return [
                    'id' => $risk->id,
                    'name' => $risk->name,
                    'description' => $risk->description,
                    'probability' => $risk->probability,
                    'impact' => $risk->impact,
                    'risk_level' => $risk->risk_level,
                    'category' => $risk->category,
                    'status' => $risk->status,
                ];
            })->toArray(),
            'decisions' => $plan->decisions->pluck('id')->toArray(),
            'clients' => $plan->clients->pluck('id')->toArray(),
            'projects' => $plan->projects->pluck('id')->toArray(),
        ];
    }
    
    /**
     * Restaurar un plan a una versión específica
     */
    public function restoreVersion(PlanVersion $version): Plan
    {
        $plan = $version->plan;
        $snapshot = $version->snapshot;
        
        DB::transaction(function () use ($plan, $snapshot) {
            // Restaurar datos del plan
            $planData = $snapshot['plan'];
            unset($planData['id'], $planData['version']);
            $plan->update($planData);
            
            // Nota: No restauramos secciones, KPIs, tareas, etc. automáticamente
            // porque podrían tener relaciones con otros recursos
            // Esto se puede hacer manualmente si es necesario
        });
        
        return $plan->fresh();
    }
    
    /**
     * Comparar dos versiones del plan
     */
    public function compareVersions(PlanVersion $version1, PlanVersion $version2): array
    {
        $snapshot1 = $version1->snapshot;
        $snapshot2 = $version2->snapshot;
        
        $differences = [];
        
        // Comparar datos del plan
        $plan1 = $snapshot1['plan'];
        $plan2 = $snapshot2['plan'];
        
        foreach ($plan1 as $key => $value) {
            if (isset($plan2[$key]) && $plan2[$key] != $value) {
                $differences['plan'][$key] = [
                    'old' => $value,
                    'new' => $plan2[$key],
                ];
            }
        }
        
        // Comparar secciones
        $differences['sections'] = $this->compareArrays(
            $snapshot1['sections'] ?? [],
            $snapshot2['sections'] ?? [],
            'id'
        );
        
        // Comparar KPIs
        $differences['kpis'] = $this->compareArrays(
            $snapshot1['kpis'] ?? [],
            $snapshot2['kpis'] ?? [],
            'id'
        );
        
        // Comparar milestones
        $differences['milestones'] = $this->compareArrays(
            $snapshot1['milestones'] ?? [],
            $snapshot2['milestones'] ?? [],
            'id'
        );
        
        return $differences;
    }
    
    /**
     * Comparar dos arrays de elementos
     */
    protected function compareArrays(array $array1, array $array2, string $keyField): array
    {
        $differences = [
            'added' => [],
            'removed' => [],
            'modified' => [],
        ];
        
        $map1 = collect($array1)->keyBy($keyField);
        $map2 = collect($array2)->keyBy($keyField);
        
        // Elementos agregados
        foreach ($map2 as $key => $item) {
            if (!$map1->has($key)) {
                $differences['added'][] = $item;
            }
        }
        
        // Elementos eliminados
        foreach ($map1 as $key => $item) {
            if (!$map2->has($key)) {
                $differences['removed'][] = $item;
            }
        }
        
        // Elementos modificados
        foreach ($map1 as $key => $item1) {
            if ($map2->has($key)) {
                $item2 = $map2[$key];
                $itemDiff = [];
                foreach ($item1 as $field => $value) {
                    if (isset($item2[$field]) && $item2[$field] != $value) {
                        $itemDiff[$field] = [
                            'old' => $value,
                            'new' => $item2[$field],
                        ];
                    }
                }
                if (!empty($itemDiff)) {
                    $differences['modified'][] = [
                        'id' => $key,
                        'changes' => $itemDiff,
                    ];
                }
            }
        }
        
        return $differences;
    }
}

