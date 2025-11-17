<?php

namespace App\Livewire\Plans;

use App\Models\Plan;
use App\Models\Client;
use App\Models\Project;
use Livewire\Component;

class PlanSectorAnalysis extends Component
{
    public Plan $plan;
    public $selectedSector = null;
    public $sectorData = [];
    public $commercialMetrics = [];

    public function mount(Plan $plan)
    {
        $this->plan = $plan;
        $this->loadSectorData();
        $this->loadCommercialMetrics();
    }

    public function selectSector($sector)
    {
        $this->selectedSector = $sector;
        $this->loadSectorData();
    }

    public function loadSectorData()
    {
        // Obtener clientes y proyectos relacionados con este plan comercial
        $planClients = $this->plan->clients;
        $planProjects = $this->plan->projects;

        // Obtener sectores únicos de los clientes y proyectos del plan
        $sectors = collect();
        
        foreach ($planClients as $client) {
            if ($client->sector_economico) {
                $sectors->push($client->sector_economico);
            }
        }
        
        foreach ($planProjects as $project) {
            if ($project->sector_economico) {
                $sectors->push($project->sector_economico);
            }
        }

        $uniqueSectors = $sectors->unique()->values();

        $this->sectorData = $uniqueSectors->map(function ($sector) use ($planClients, $planProjects) {
            $sectorClients = $planClients->where('sector_economico', $sector);
            $sectorProjects = $planProjects->where('sector_economico', $sector);
            
            $totalBudget = $sectorProjects->sum('presupuesto');
            $activeProjects = $sectorProjects->where('status', 'activo')->count();
            $completedProjects = $sectorProjects->where('status', 'completado')->count();
            $pendingProjects = $sectorProjects->where('status', 'pendiente')->count();

            return [
                'sector' => $sector,
                'clients_count' => $sectorClients->count(),
                'projects_count' => $sectorProjects->count(),
                'active_projects' => $activeProjects,
                'completed_projects' => $completedProjects,
                'pending_projects' => $pendingProjects,
                'total_budget' => $totalBudget,
                'average_budget' => $sectorProjects->count() > 0 ? $totalBudget / $sectorProjects->count() : 0,
            ];
        })->sortByDesc('total_budget')->values()->toArray();
    }

    public function loadCommercialMetrics()
    {
        $planClients = $this->plan->clients;
        $planProjects = $this->plan->projects;

        $this->commercialMetrics = [
            'total_clients' => $planClients->count(),
            'total_projects' => $planProjects->count(),
            'total_budget' => $planProjects->sum('presupuesto'),
            'active_projects' => $planProjects->where('status', 'activo')->count(),
            'completed_projects' => $planProjects->where('status', 'completado')->count(),
            'pending_projects' => $planProjects->where('status', 'pendiente')->count(),
            'sectors_count' => collect($this->sectorData)->count(),
            'average_project_budget' => $planProjects->count() > 0 ? $planProjects->sum('presupuesto') / $planProjects->count() : 0,
        ];
    }

    public function render()
    {
        $selectedData = null;
        if ($this->selectedSector) {
            $selectedData = collect($this->sectorData)->firstWhere('sector', $this->selectedSector);
        }

        return view('livewire.plans.plan-sector-analysis', [
            'selectedData' => $selectedData,
        ]);
    }
}

