<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use App\Models\Client;
use Livewire\Component;
use Livewire\WithPagination;

class ProjectList extends Component
{
    use WithPagination;

    public $search = '';
    public $client_id = '';
    public $status = '';
    public $sector_economico = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'client_id' => ['except' => ''],
        'status' => ['except' => ''],
        'sector_economico' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingClientId()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingSectorEconomico()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Project::query()->with(['client', 'manager', 'planComercial']);

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        if ($this->client_id) {
            $query->where('client_id', $this->client_id);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->sector_economico) {
            $query->where('sector_economico', $this->sector_economico);
        }

        $projects = $query->latest()->paginate(15);

        $clients = Client::orderBy('name')->get();
        $sectors = Project::distinct()->whereNotNull('sector_economico')->pluck('sector_economico');

        return view('livewire.projects.project-list', [
            'projects' => $projects,
            'clients' => $clients,
            'sectors' => $sectors,
        ]);
    }
}
