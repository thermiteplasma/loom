<?php

namespace Thermiteplasma\Loom\Components;

use Illuminate\View\Component;
use Thermiteplasma\Loom\Concerns\{BuildsStyles, HasTypography, HasBorders, HasBackground};

class Style extends Component
{
    use BuildsStyles, HasTypography, HasBorders, HasBackground;

    public function __construct(
        public string $name,
        public ?string $extends = null,
        public ?string $padding = null,
        ?string $fontFamily = null,
        ?string $fontSize = null,
        ?string $color = null,
        bool $bold = false,
        bool $italic = false,
        bool $underline = false,
        ?string $align = null,
        ?string $lineHeight = null,
        bool|string $border = false,
        string $borderStyle = 'solid',
        string $borderWidth = '0.5pt',
        string $borderColor = '#cccccc',
        ?string $background = null,
    ) {
        $this->fontFamily = $fontFamily;
        $this->fontSize = $fontSize;
        $this->color = $color;
        $this->bold = $bold;
        $this->italic = $italic;
        $this->underline = $underline;
        $this->align = $align;
        $this->lineHeight = $lineHeight;
        $this->border = $border;
        $this->borderStyle = $borderStyle;
        $this->borderWidth = $borderWidth;
        $this->borderColor = $borderColor;
        $this->background = $background;
    }

    public function cssRule(): string
    {
        $declarations = $this->buildStyleString(array_merge(
            ['padding' => $this->padding],
            $this->typographyStyles(),
            $this->borderStyles(),
            $this->backgroundStyles(),
        ));

        return ".style-{$this->name} { {$declarations} }";
    }

    public function render()
    {
        return view('loom::components.style');
    }
}