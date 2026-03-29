<?php

namespace Thermiteplasma\Loom\Components\Bands;

use Thermiteplasma\Loom\Components\Band;

class PageHeader extends Band
{
    public function __construct(
        ?string $padding = '3mm 0',
        ...$props,
    ) {
        parent::__construct(...array_merge(['bandType' => 'pageHeader', 'padding' => $padding, 'keepTogether' => true], $props));
    }
}
