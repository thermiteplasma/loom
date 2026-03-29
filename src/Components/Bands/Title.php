<?php

namespace Thermiteplasma\Loom\Components\Bands;

use Thermiteplasma\Loom\Components\Band;

class Title extends Band
{
    public function __construct(
        bool $breakAfter = true,
        ?string $padding = '8mm',
        ?string $align = 'center',
        ...$props,
    ) {
        parent::__construct(...array_merge(['bandType' => 'title', 'breakAfter' => $breakAfter, 'padding' => $padding, 'align' => $align], $props));
    }
}
