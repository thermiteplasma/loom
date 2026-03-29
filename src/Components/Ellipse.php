<?php

namespace Thermiteplasma\Loom\Components;

class Ellipse extends Rectangle
{
    public function __construct(?string $width = '20mm', ?string $height = '20mm', ...$props)
    {
        parent::__construct(...array_merge(['width' => $width, 'height' => $height, 'borderRadius' => '50%'], $props));
    }

    public function render()
    {
        return view('loom::components.ellipse');
    }
}
