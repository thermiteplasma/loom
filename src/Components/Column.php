<?php

namespace Thermiteplasma\Loom\Components;

use Illuminate\View\Component;
use Thermiteplasma\Loom\Concerns\BuildsStyles;
use Thermiteplasma\Loom\Concerns\HasBoxModel;
use Thermiteplasma\Loom\Concerns\HasTypography;

class Column extends Component
{
    use BuildsStyles, HasBoxModel, HasTypography;

    public function __construct(
        public ?string $flex = null,
        public ?string $basis = null,
        ?string $width = null,
        ?string $padding = null,
        ?string $align = null,
        ?string $verticalAlign = null,
    ) {
        $this->width = $width;
        $this->padding = $padding;
        $this->align = $align;
        $this->verticalAlign = $verticalAlign;
    }

    public function styles(): string
    {
        return $this->buildStyleString(array_merge(
            $this->boxModelStyles(),
            $this->typographyStyles(),
            [
                'flex' => $this->flex,
                'flex-basis' => $this->basis,
            ],
        ));
    }

    public function render()
    {
        return view('loom::components.column');
    }
}
