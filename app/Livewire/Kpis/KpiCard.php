<?php

namespace App\Livewire\Kpis;

use App\Models\Kpi;
use Livewire\Component;

class KpiCard extends Component
{
    public Kpi $kpi;
    public $showDetails = false;

    public function mount(Kpi $kpi)
    {
        $this->kpi = $kpi;
    }

    public function toggleDetails()
    {
        $this->showDetails = !$this->showDetails;
    }

    public function render()
    {
        return view('livewire.kpis.kpi-card');
    }
}
