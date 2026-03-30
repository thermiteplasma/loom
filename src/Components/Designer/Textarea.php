<?php

namespace Thermiteplasma\Loom\Components\Designer;

use Illuminate\View\Component;

class Textarea extends Component
{
    public function __construct(
        public string $label,
        public string $prop,
        public string $placeholder = '',
    ) {}

    public function render()
    {
        return view('loom::components.designer.textarea');
    }
}
