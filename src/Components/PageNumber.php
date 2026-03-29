<?php

namespace Thermiteplasma\Loom\Components;

use Illuminate\View\Component;
use Thermiteplasma\Loom\Concerns\{BuildsStyles, HasTypography};

class PageNumber extends Component
{
    use BuildsStyles, HasTypography;

    public function __construct(
        public string $prefix = 'Page ',
        public bool $showTotal = false,
        public string $separator = ' of ',
        ?string $fontSize = null,
        ?string $color = null,
    ) {
        $this->fontSize = $fontSize;
        $this->color = $color;
    }

    public function styles(): string
    {
        return $this->buildStyleString($this->typographyStyles());
    }

    public function render()
    {
        return view('loom::components.page-number');
    }
}