<?php

namespace Thermiteplasma\Loom\Components\Bands;

use Thermiteplasma\Loom\Components\Band;

class ColumnFooter extends Band
{
    public function __construct(
        ?string $padding = '2mm 3mm',
        bool|string $borderTop = true,
        ...$props,
    ) {
        parent::__construct(...array_merge(['bandType' => 'columnFooter', 'padding' => $padding, 'borderTop' => $borderTop], $props));
    }
}
