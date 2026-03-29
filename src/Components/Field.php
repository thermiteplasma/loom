<?php

namespace Thermiteplasma\Loom\Components;

use Illuminate\View\Component;
use Thermiteplasma\Loom\Concerns\{BuildsStyles, HasBoxModel, HasTypography, HasBorders, HasBackground};

class Field extends Component
{
    use BuildsStyles, HasBoxModel, HasTypography, HasBorders, HasBackground;

    public function __construct(
        ?string $width = null,
        ?string $height = null,
        ?string $minHeight = null,
        ?string $maxHeight = null,
        ?string $padding = null,
        ?string $paddingTop = null,
        ?string $paddingBottom = null,
        ?string $paddingLeft = null,
        ?string $paddingRight = null,
        ?string $margin = null,
        ?string $marginTop = null,
        ?string $marginBottom = null,
        ?string $fontFamily = null,
        ?string $fontSize = null,
        ?string $color = null,
        bool $bold = false,
        bool $italic = false,
        bool $underline = false,
        bool $strikethrough = false,
        ?string $align = null,
        ?string $verticalAlign = null,
        ?string $lineHeight = null,
        ?string $letterSpacing = null,
        ?string $textTransform = null,
        ?string $whiteSpace = null,
        bool|string $border = false,
        bool|string $borderTop = false,
        bool|string $borderBottom = false,
        bool|string $borderLeft = false,
        bool|string $borderRight = false,
        string $borderStyle = 'solid',
        string $borderWidth = '0.5pt',
        string $borderColor = '#cccccc',
        ?string $borderRadius = null,
        ?string $background = null,
        ?string $opacity = null,
        public ?string $format = null,
        public bool $stretchWithOverflow = false,
        public string $tag = 'span',
    ) {
        $this->width = $width;
        $this->height = $height;
        $this->minHeight = $minHeight;
        $this->maxHeight = $maxHeight;
        $this->padding = $padding;
        $this->paddingTop = $paddingTop;
        $this->paddingBottom = $paddingBottom;
        $this->paddingLeft = $paddingLeft;
        $this->paddingRight = $paddingRight;
        $this->margin = $margin;
        $this->marginTop = $marginTop;
        $this->marginBottom = $marginBottom;
        $this->fontFamily = $fontFamily;
        $this->fontSize = $fontSize;
        $this->color = $color;
        $this->bold = $bold;
        $this->italic = $italic;
        $this->underline = $underline;
        $this->strikethrough = $strikethrough;
        $this->align = $align;
        $this->verticalAlign = $verticalAlign;
        $this->lineHeight = $lineHeight;
        $this->letterSpacing = $letterSpacing;
        $this->textTransform = $textTransform;
        $this->whiteSpace = $whiteSpace;
        $this->border = $border;
        $this->borderTop = $borderTop;
        $this->borderBottom = $borderBottom;
        $this->borderLeft = $borderLeft;
        $this->borderRight = $borderRight;
        $this->borderStyle = $borderStyle;
        $this->borderWidth = $borderWidth;
        $this->borderColor = $borderColor;
        $this->borderRadius = $borderRadius;
        $this->background = $background;
        $this->opacity = $opacity;
    }

    public function styles(): string
    {
        return $this->buildStyleString(array_merge(
            $this->boxModelStyles(),
            $this->typographyStyles(),
            $this->borderStyles(),
            $this->backgroundStyles(),
            [
                'display' => $this->width ? 'inline-block' : null,
                'overflow' => $this->stretchWithOverflow ? 'visible' : null,
            ],
        ));
    }

    public function render()
    {
        return view('loom::components.field');
    }
}