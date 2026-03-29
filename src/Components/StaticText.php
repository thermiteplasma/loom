<?php

namespace Thermiteplasma\Loom\Components;

class StaticText extends Field
{
    public function __construct(
        ?string $fontSize = null,
        ?string $color = '#666',
        bool $bold = false,
        string $tag = 'span',
        ...$props,
    ) {
        parent::__construct(...array_merge(['fontSize' => $fontSize, 'color' => $color, 'bold' => $bold, 'tag' => $tag], $props));
    }

    public function render()
    {
        return view('loom::components.static-text');
    }
}