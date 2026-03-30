<?php

namespace Thermiteplasma\Loom\Components\Designer;

use Illuminate\View\Component;

class Select extends Component
{
    public function __construct(
        public string $label,
        public string $prop,
        public array $options = [],
    ) {}

    public function render()
    {
        return view('loom::components.designer.select');
    }
}
