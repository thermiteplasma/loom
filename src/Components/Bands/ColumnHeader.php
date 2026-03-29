<?php

namespace Thermiteplasma\Loom\Components\Bands;

use Thermiteplasma\Loom\Components\Band;

class ColumnHeader extends Band
{
    public function __construct(
        ?string $padding = '2mm 3mm',
        bool $bold = true,
        ?string $fontSize = '8pt',
        ?string $background = '#f4f4f4',
        bool|string $borderBottom = true,
        ...$props,
    ) {
        parent::__construct(...array_merge(['bandType' => 'columnHeader', 'padding' => $padding, 'bold' => $bold, 'fontSize' => $fontSize, 'background' => $background, 'borderBottom' => $borderBottom], $props));
    }
}
