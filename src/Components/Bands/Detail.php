<?php

namespace Thermiteplasma\Loom\Components\Bands;

use Thermiteplasma\Loom\Components\Band;

class Detail extends Band
{
    public function __construct(
        ?string $padding = '1.5mm 3mm',
        bool $keepTogether = true,
        ...$props,
    ) {
        parent::__construct(...array_merge(['bandType' => 'detail', 'padding' => $padding, 'keepTogether' => $keepTogether], $props));
    }
}