<?php

namespace Thermiteplasma\Loom\Components;

use Illuminate\View\Component;

class TotalPages extends Component
{
    public function render()
    {
        return view('loom::components.total-pages');
    }
}
