<?php

namespace Thermiteplasma\Loom\Components\Bands;

use Thermiteplasma\Loom\Components\Band;

class PageFooter extends Band
{
    public function __construct(
        ?string $padding = '3mm 0',
        ?string $fontSize = '7pt',
        ?string $color = '#999999',
        ...$props,
    ) {
        parent::__construct(...array_merge(['bandType' => 'pageFooter', 'padding' => $padding, 'fontSize' => $fontSize, 'color' => $color, 'keepTogether' => true], $props));
    }
}