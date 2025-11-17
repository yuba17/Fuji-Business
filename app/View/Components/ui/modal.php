<?php

namespace App\View\Components\ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class modal extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $name = null, // Nombre del modal para Alpine.js
        public ?string $title = null,
        public ?bool $showCloseButton = true,
        public ?string $maxWidth = '2xl', // Tamaño máximo del modal
    ) {
        // Si no se proporciona name, usar un valor por defecto
        if (!$this->name) {
            $this->name = 'showModal';
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ui.modal');
    }
}
