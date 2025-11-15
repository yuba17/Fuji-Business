<?php

namespace App\Livewire\Risks;

use App\Models\Risk;
use Livewire\Component;

class RiskCard extends Component
{
    public Risk $risk;
    public $showDetails = false;

    public function mount(Risk $risk)
    {
        $this->risk = $risk;
    }

    public function toggleDetails()
    {
        $this->showDetails = !$this->showDetails;
    }

    public function render()
    {
        return view('livewire.risks.risk-card');
    }
}
