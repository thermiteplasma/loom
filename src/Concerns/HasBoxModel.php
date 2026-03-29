<?php 

namespace Thermiteplasma\Loom\Concerns;

trait HasBoxModel
{
    public ?string $width = null;
    public ?string $height = null;
    public ?string $minHeight = null;
    public ?string $maxHeight = null;
    public ?string $padding = null;
    public ?string $paddingTop = null;
    public ?string $paddingBottom = null;
    public ?string $paddingLeft = null;
    public ?string $paddingRight = null;
    public ?string $margin = null;
    public ?string $marginTop = null;
    public ?string $marginBottom = null;

    protected function boxModelStyles(): array
    {
        return [
            'width' => $this->width,
            'height' => $this->height,
            'min-height' => $this->minHeight,
            'max-height' => $this->maxHeight,
            'padding' => $this->padding,
            'padding-top' => $this->paddingTop,
            'padding-bottom' => $this->paddingBottom,
            'padding-left' => $this->paddingLeft,
            'padding-right' => $this->paddingRight,
            'margin' => $this->margin,
            'margin-top' => $this->marginTop,
            'margin-bottom' => $this->marginBottom,
        ];
    }
}