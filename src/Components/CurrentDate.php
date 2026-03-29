<?php

namespace Thermiteplasma\Loom\Components;

use Illuminate\View\Component;
use Thermiteplasma\Loom\Concerns\{BuildsStyles, HasTypography};

class CurrentDate extends Component
{
    use BuildsStyles, HasTypography;

    public function __construct(
        public string $format = 'd/m/Y H:i',
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
        return view('loom::components.current-date');
    }
}