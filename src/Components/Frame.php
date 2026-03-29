<?php

namespace Thermiteplasma\Loom\Components;

use Illuminate\View\Component;
use Thermiteplasma\Loom\Concerns\{BuildsStyles, HasBoxModel, HasTypography, HasBorders, HasBackground};

class Frame extends Component
{
    use BuildsStyles, HasBoxModel, HasTypography, HasBorders, HasBackground;

    public function __construct(
        ?string $width = null,
        ?string $height = null,
        ?string $padding = null,
        ?string $margin = null,
        ?string $background = null,
        ?string $opacity = null,
        bool|string $border = false,
        string $borderStyle = 'solid',
        string $borderWidth = '0.5pt',
        string $borderColor = '#cccccc',
        ?string $borderRadius = null,
        ?string $fontFamily = null,
        ?string $fontSize = null,
        ?string $color = null,
        bool $bold = false,
        bool $italic = false,
        ?string $align = null,
        public string $display = 'block',
        public ?string $gap = null,
        public ?string $justify = null,
        public ?string $items = null,
        public ?string $direction = null,
        public ?string $wrap = null,
        public bool $visible = true,
    ) {
        $this->width = $width;
        $this->height = $height;
        $this->padding = $padding;
        $this->margin = $margin;
        $this->background = $background;
        $this->opacity = $opacity;
        $this->border = $border;
        $this->borderStyle = $borderStyle;
        $this->borderWidth = $borderWidth;
        $this->borderColor = $borderColor;
        $this->borderRadius = $borderRadius;
        $this->fontFamily = $fontFamily;
        $this->fontSize = $fontSize;
        $this->color = $color;
        $this->bold = $bold;
        $this->italic = $italic;
        $this->align = $align;
    }

    public function styles(): string
    {
        return $this->buildStyleString(array_merge(
            $this->boxModelStyles(),
            $this->typographyStyles(),
            $this->borderStyles(),
            $this->backgroundStyles(),
            [
                'display' => $this->display !== 'block' ? $this->display : null,
                'gap' => $this->gap,
                'justify-content' => $this->justify,
                'align-items' => $this->items,
                'flex-direction' => $this->direction,
                'flex-wrap' => $this->wrap,
            ],
        ));
    }

    public function render()
    {
        return view('loom::components.frame');
    }
}
