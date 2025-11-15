<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use App\Models\Project;
use Livewire\Component;

class SectorAnalysis extends Component
{
    public $sectorData = [];
    public $selectedSector = null;

    public function mount($selectedSector = null)
    {
        $this->selectedSector = $selectedSector;
        $this->loadSectorData();
    }

    public function selectSector($sector)
    {
        $this->selectedSector = $sector;
        $this->loadSectorData();
    }

    public function loadSectorData()
    {
        $sectors = Client::distinct()->whereNotNull('sector_economico')->pluck('sector_economico');

        $this->sectorData = $sectors->map(function ($sector) {
            $clients = Client::where('sector_economico', $sector)->get();
            $projects = Project::where('sector_economico', $sector)->get();
            
            $totalBudget = $projects->sum('presupuesto');
            $activeProjects = $projects->where('status', 'activo')->count();
            $completedProjects = $projects->where('status', 'completado')->count();

            return [
                'sector' => $sector,
                'clients_count' => $clients->count(),
                'projects_count' => $projects->count(),
                'active_projects' => $activeProjects,
                'completed_projects' => $completedProjects,
                'total_budget' => $totalBudget,
                'average_budget' => $projects->count() > 0 ? $totalBudget / $projects->count() : 0,
            ];
        })->sortByDesc('clients_count')->values()->toArray();
    }

    public function render()
    {
        $selectedData = null;
        if ($this->selectedSector) {
            $selectedData = collect($this->sectorData)->firstWhere('sector', $this->selectedSector);
        }

        return view('livewire.clients.sector-analysis', [
            'selectedData' => $selectedData,
        ]);
    }
}
