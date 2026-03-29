<?php

namespace Thermiteplasma\Loom\Components;

use Illuminate\View\Component;
use Thermiteplasma\Loom\Concerns\{BuildsStyles, HasBorders, HasTypography};

class Table extends Component
{
    use BuildsStyles, HasBorders, HasTypography;

    public function __construct(
        public ?string $width = '100%',
        public bool $striped = false,
        public ?string $stripeColor = '#f9f9f9',
        public bool $repeatHeader = true,
        ?string $fontSize = null,
        bool|string $border = false,
        string $borderWidth = '0.5pt',
        string $borderColor = '#dddddd',
        string $borderStyle = 'solid',
    ) {
        $this->fontSize = $fontSize;
        $this->border = $border;
        $this->borderWidth = $borderWidth;
        $this->borderColor = $borderColor;
        $this->borderStyle = $borderStyle;
    }

    public function styles(): string
    {
        return $this->buildStyleString(array_merge(
            ['width' => $this->width, 'border-collapse' => 'collapse'],
            $this->typographyStyles(),
            $this->borderStyles(),
        ));
    }

    public function render()
    {
        return view('loom::components.table');
    }
}