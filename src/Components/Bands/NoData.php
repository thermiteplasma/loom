<?php

namespace Thermiteplasma\Loom\Components\Bands;

use Thermiteplasma\Loom\Components\Band;

class NoData extends Band
{
    public function __construct(
        ?string $padding = '10mm',
        ?string $align = 'center',
        ?string $color = '#999',
        ...$props,
    ) {
        parent::__construct(...array_merge(['bandType' => 'noData', 'padding' => $padding, 'align' => $align, 'color' => $color], $props));
    }
}