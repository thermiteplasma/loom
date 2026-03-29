<?php

namespace Thermiteplasma\Loom\Components\Bands;

use Thermiteplasma\Loom\Components\Band;

class GroupFooter extends Band
{
    public function __construct(
        ?string $padding = '3mm',
        bool $bold = true,
        ...$props,
    ) {
        parent::__construct(...array_merge(['bandType' => 'groupFooter', 'padding' => $padding, 'bold' => $bold], $props));
    }
}
