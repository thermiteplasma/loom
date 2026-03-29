<?php

namespace Thermiteplasma\Loom\Components;

use Illuminate\View\Component;

class PageBreak extends Component
{
    public function render()
    {
        return view('loom::components.page-break');
    }
}