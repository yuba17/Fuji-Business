<?php

namespace App\View\Components\ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class card extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $variant = 'default', // default, gradient, compact
        public ?string $borderColor = 'red', // red, orange, blue, purple, green
        public ?bool $withBorder = true,
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ui.card');
    }
}
