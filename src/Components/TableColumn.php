<?php

namespace Thermiteplasma\Loom\Components;

use Illuminate\View\Component;
use Thermiteplasma\Loom\Concerns\{BuildsStyles, HasTypography, HasBackground, HasBorders};

class TableColumn extends Component
{
    use BuildsStyles, HasTypography, HasBackground, HasBorders;

    public function __construct(
        public ?string $header = null,
        public ?string $width = null,
        public ?string $padding = '2mm 3mm',
        ?string $align = null,
        bool $bold = false,
        ?string $fontSize = null,
        ?string $color = null,
        ?string $background = null,
        bool|string $borderBottom = false,
        string $borderWidth = '0.5pt',
        string $borderColor = '#dddddd',
    ) {
        $this->align = $align;
        $this->bold = $bold;
        $this->fontSize = $fontSize;
        $this->color = $color;
        $this->background = $background;
        $this->borderBottom = $borderBottom;
        $this->borderWidth = $borderWidth;
        $this->borderColor = $borderColor;
    }

    public function cellStyles(): string
    {
        return $this->buildStyleString(array_merge(
            ['width' => $this->width, 'padding' => $this->padding],
            $this->typographyStyles(),
            $this->backgroundStyles(),
            $this->borderStyles(),
        ));
    }

    public function render()
    {
        return '';
    }
}