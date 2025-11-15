<?php

namespace App\Livewire\Dashboards;

use Livewire\Component;

class DashboardWidget extends Component
{
    public $title;
    public $icon;
    public $color = 'blue';
    public $size = 'medium'; // small, medium, large

    public function mount($title = '', $icon = '', $color = 'blue', $size = 'medium')
    {
        $this->title = $title;
        $this->icon = $icon;
        $this->color = $color;
        $this->size = $size;
    }

    public function render()
    {
        return view('livewire.dashboards.dashboard-widget');
    }
}
