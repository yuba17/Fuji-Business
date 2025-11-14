<?php

namespace App\View\Components\ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class badge extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $variant = 'success', // success, warning, error, info, purple
        public ?string $icon = null,
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ui.badge');
    }
}
