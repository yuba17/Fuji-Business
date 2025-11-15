<?php

namespace App\Livewire\Risks;

use App\Models\Risk;
use Livewire\Component;

class MitigationActionList extends Component
{
    public Risk $risk;

    public function mount(Risk $risk)
    {
        $this->risk = $risk;
    }

    public function render()
    {
        $this->risk->load('mitigationActions.responsible');
        return view('livewire.risks.mitigation-action-list');
    }
}
