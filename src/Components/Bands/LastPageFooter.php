<?php

namespace Thermiteplasma\Loom\Components\Bands;

use Thermiteplasma\Loom\Components\Band;

class LastPageFooter extends Band
{
    public function __construct(?string $padding = '3mm 0', ...$props)
    {
        parent::__construct(...array_merge(['bandType' => 'lastPageFooter', 'padding' => $padding, 'keepTogether' => true], $props));
    }
}