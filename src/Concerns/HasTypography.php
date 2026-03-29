<?php 

namespace Thermiteplasma\Loom\Concerns;

trait HasTypography
{
    public ?string $fontFamily = null;
    public ?string $fontSize = null;
    public ?string $color = null;
    public bool $bold = false;
    public bool $italic = false;
    public bool $underline = false;
    public bool $strikethrough = false;
    public ?string $align = null;
    public ?string $verticalAlign = null;
    public ?string $lineHeight = null;
    public ?string $letterSpacing = null;
    public ?string $textTransform = null;
    public ?string $whiteSpace = null;

    protected function typographyStyles(): array
    {
        return [
            'font-family' => $this->fontFamily,
            'font-size' => $this->fontSize,
            'color' => $this->color,
            'font-weight' => $this->bold ? 'bold' : null,
            'font-style' => $this->italic ? 'italic' : null,
            'text-decoration' => $this->textDecoration(),
            'text-align' => $this->align,
            'vertical-align' => $this->verticalAlign,
            'line-height' => $this->lineHeight,
            'letter-spacing' => $this->letterSpacing,
            'text-transform' => $this->textTransform,
            'white-space' => $this->whiteSpace,
        ];
    }

    protected function textDecoration(): ?string
    {
        $decorations = array_filter([
            $this->underline ? 'underline' : null,
            $this->strikethrough ? 'line-through' : null,
        ]);

        return $decorations ? implode(' ', $decorations) : null;
    }
}