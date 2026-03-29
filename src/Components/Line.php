<?php

namespace Thermiteplasma\Loom\Components;

use Illuminate\View\Component;
use Thermiteplasma\Loom\Concerns\BuildsStyles;

class Line extends Component
{
    use BuildsStyles;

    public function __construct(
        public string $direction = 'horizontal',
        public ?string $width = null,
        public ?string $height = null,
        public string $lineStyle = 'solid',
        public string $lineWidth = '0.5pt',
        public string $lineColor = '#cccccc',
        public ?string $margin = null,
    ) {}

    public function styles(): string
    {
        if ($this->direction === 'vertical') {
            return $this->buildStyleString([
                'display' => 'inline-block',
                'width' => '0',
                'height' => $this->height ?? '100%',
                'border-left' => "{$this->lineWidth} {$this->lineStyle} {$this->lineColor}",
                'margin' => $this->margin,
            ]);
        }

        return $this->buildStyleString([
            'width' => $this->width ?? '100%',
            'height' => '0',
            'border-top' => "{$this->lineWidth} {$this->lineStyle} {$this->lineColor}",
            'margin' => $this->margin ?? '2mm 0',
        ]);
    }

    public function render()
    {
        return view('loom::components.line');
    }
}
