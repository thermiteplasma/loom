<?php

namespace Thermiteplasma\Loom\Components\Designer;

use Illuminate\View\Component;

class PropSection extends Component
{
    public function __construct(public string $label) {}

    public function render()
    {
        return view('loom::components.designer.prop-section');
    }
}
