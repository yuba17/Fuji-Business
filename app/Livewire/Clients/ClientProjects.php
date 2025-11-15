<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use App\Models\Project;
use Livewire\Component;
use Livewire\WithPagination;

class ClientProjects extends Component
{
    use WithPagination;

    public Client $client;
    public $status = '';
    public $search = '';

    protected $queryString = [
        'status' => ['except' => ''],
        'search' => ['except' => ''],
    ];

    public function mount(Client $client)
    {
        $this->client = $client;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = $this->client->projects()->with(['manager', 'planComercial']);

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        $projects = $query->latest()->paginate(10);

        return view('livewire.clients.client-projects', [
            'projects' => $projects,
        ]);
    }
}
