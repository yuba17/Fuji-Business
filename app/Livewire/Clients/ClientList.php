<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use Livewire\Component;
use Livewire\WithPagination;

class ClientList extends Component
{
    use WithPagination;

    public $search = '';
    public $sector_economico = '';
    public $tamaño_empresa = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'sector_economico' => ['except' => ''],
        'tamaño_empresa' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSectorEconomico()
    {
        $this->resetPage();
    }

    public function updatingTamañoEmpresa()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Client::query()->withCount('projects');

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('contacto_principal', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
        }

        if ($this->sector_economico) {
            $query->where('sector_economico', $this->sector_economico);
        }

        if ($this->tamaño_empresa) {
            $query->where('tamaño_empresa', $this->tamaño_empresa);
        }

        $clients = $query->latest()->paginate(15);

        $sectors = Client::distinct()->whereNotNull('sector_economico')->pluck('sector_economico');
        $sizes = Client::distinct()->whereNotNull('tamaño_empresa')->pluck('tamaño_empresa');

        return view('livewire.clients.client-list', [
            'clients' => $clients,
            'sectors' => $sectors,
            'sizes' => $sizes,
        ]);
    }
}
