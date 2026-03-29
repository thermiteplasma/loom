<?php

namespace Thermiteplasma\Loom\Components;

use Illuminate\View\Component;
use Thermiteplasma\Loom\Concerns\BuildsStyles;
use Thermiteplasma\Loom\Concerns\HasBoxModel;

class Row extends Component
{
    use BuildsStyles, HasBoxModel;

    public function __construct(
        public ?string $gap = '3mm',
        public string $justify = 'flex-start',
        public string $items = 'stretch',
        public ?string $wrap = null,
        ?string $padding = null,
        ?string $margin = null,
    ) {
        $this->padding = $padding;
        $this->margin = $margin;
    }

    public function styles(): string
    {
        return $this->buildStyleString(array_merge(
            $this->boxModelStyles(),
            [
                'display' => 'flex',
                'flex-direction' => 'row',
                'gap' => $this->gap,
                'justify-content' => $this->justify,
                'align-items' => $this->items,
                'flex-wrap' => $this->wrap,
            ],
        ));
    }

    public function render()
    {
        return view('loom::components.row');
    }
}
