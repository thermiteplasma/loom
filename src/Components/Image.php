<?php

namespace Thermiteplasma\Loom\Components;

use Illuminate\View\Component;
use Thermiteplasma\Loom\Concerns\{BuildsStyles, HasBorders};

class Image extends Component
{
    use BuildsStyles, HasBorders;

    public function __construct(
        public string $src,
        public string $alt = '',
        public ?string $width = null,
        public ?string $height = null,
        public string $fit = 'contain',
        public ?string $align = null,
        public ?string $padding = null,
        public ?string $background = null,
        bool|string $border = false,
        string $borderStyle = 'solid',
        string $borderWidth = '0.5pt',
        string $borderColor = '#cccccc',
        ?string $borderRadius = null,
    ) {
        $this->border = $border;
        $this->borderStyle = $borderStyle;
        $this->borderWidth = $borderWidth;
        $this->borderColor = $borderColor;
        $this->borderRadius = $borderRadius;
    }

    public function styles(): string
    {
        return $this->buildStyleString(array_merge(
            [
                'width' => $this->width,
                'height' => $this->height,
                'object-fit' => $this->fit,
                'padding' => $this->padding,
                'background' => $this->background,
                'display' => 'block',
                'margin' => match ($this->align) {
                    'center' => '0 auto',
                    'right' => '0 0 0 auto',
                    default => null,
                },
            ],
            $this->borderStyles(),
        ));
    }

    public function render()
    {
        return view('loom::components.image');
    }
}