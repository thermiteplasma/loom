<?php

namespace Thermiteplasma\Loom\Components;

use Illuminate\View\Component;

class Report extends Component
{
    public function __construct(
        public string $size = 'A4',
        public string $orientation = 'portrait',
        public string $margin = '15mm',
        public ?string $marginTop = null,
        public ?string $marginBottom = null,
        public ?string $marginLeft = null,
        public ?string $marginRight = null,
        public string $fontFamily = 'Helvetica Neue, Helvetica, Arial, sans-serif',
        public string $fontSize = '9pt',
        public string $color = '#333333',
        public string $lineHeight = '1.4',
    ) {
        // Apply config defaults
        $defaults = config('loom.defaults', []);
        $this->size = $size !== 'A4' ? $size : ($defaults['size'] ?? 'A4');
        $this->fontFamily = $fontFamily !== 'Helvetica Neue, Helvetica, Arial, sans-serif'
            ? $fontFamily : ($defaults['font_family'] ?? $fontFamily);
    }

    public function pageSize(): string
    {
        $size = $this->size;
        if ($this->orientation === 'landscape' && ! str_contains($size, 'landscape')) {
            $size .= ' landscape';
        }

        return $size;
    }

    public function pageMargin(): string
    {
        if ($this->marginTop || $this->marginBottom || $this->marginLeft || $this->marginRight) {
            return collect([
                $this->marginTop ?? $this->margin,
                $this->marginRight ?? $this->margin,
                $this->marginBottom ?? $this->margin,
                $this->marginLeft ?? $this->margin,
            ])->implode(' ');
        }

        return $this->margin;
    }

    public function render()
    {
        return view('loom::components.report');
    }
}
