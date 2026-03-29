<?php

namespace Thermiteplasma\Loom\Components;

use Illuminate\View\Component;
use Thermiteplasma\Loom\Concerns\{BuildsStyles, HasBoxModel, HasBorders, HasBackground};

class Rectangle extends Component
{
    use BuildsStyles, HasBoxModel, HasBorders, HasBackground;

    public function __construct(
        ?string $width = null,
        ?string $height = null,
        ?string $padding = null,
        ?string $background = null,
        ?string $opacity = null,
        bool|string $border = true,
        string $borderStyle = 'solid',
        string $borderWidth = '0.5pt',
        string $borderColor = '#cccccc',
        ?string $borderRadius = null,
    ) {
        $this->width = $width;
        $this->height = $height;
        $this->padding = $padding;
        $this->background = $background;
        $this->opacity = $opacity;
        $this->border = $border;
        $this->borderStyle = $borderStyle;
        $this->borderWidth = $borderWidth;
        $this->borderColor = $borderColor;
        $this->borderRadius = $borderRadius;
    }

    public function styles(): string
    {
        return $this->buildStyleString(array_merge(
            $this->boxModelStyles(),
            $this->borderStyles(),
            $this->backgroundStyles(),
        ));
    }

    public function render()
    {
        return view('loom::components.rectangle');
    }
}