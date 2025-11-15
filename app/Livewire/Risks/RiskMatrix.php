<?php

namespace App\Livewire\Risks;

use App\Models\Risk;
use Livewire\Component;

class RiskMatrix extends Component
{
    public $risks;
    public $selectedRisk = null;

    public function mount($risks)
    {
        $this->risks = $risks;
    }

    public function selectRisk($riskId)
    {
        $this->selectedRisk = $riskId;
    }

    public function getRisksByCell($probability, $impact)
    {
        return $this->risks->filter(function($risk) use ($probability, $impact) {
            return $risk->probability == $probability && $risk->impact == $impact;
        });
    }

    public function getCellColor($probability, $impact)
    {
        $level = $probability * $impact;
        
        if ($level >= 21) {
            return 'bg-red-600';
        } elseif ($level >= 13) {
            return 'bg-orange-500';
        } elseif ($level >= 6) {
            return 'bg-yellow-400';
        } else {
            return 'bg-green-400';
        }
    }

    public function render()
    {
        return view('livewire.risks.risk-matrix');
    }
}
