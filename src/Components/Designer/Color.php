<?php

namespace Thermiteplasma\Loom\Components\Designer;

use Illuminate\View\Component;

class Color extends Component
{
    public function __construct(
        public string $label,
        public string $prop,
    ) {}

    public function render()
    {
        return view('loom::components.designer.color');
    }
}
