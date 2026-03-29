<?php

namespace Thermiteplasma\Loom\Components\Bands;

use Thermiteplasma\Loom\Components\Band;

class Summary extends Band
{
    public function __construct(
        bool $breakBefore = true,
        ?string $padding = '5mm',
        bool $bold = true,
        ...$props,
    ) {
        parent::__construct(...array_merge(['bandType' => 'summary', 'breakBefore' => $breakBefore, 'padding' => $padding, 'bold' => $bold], $props));
    }
}