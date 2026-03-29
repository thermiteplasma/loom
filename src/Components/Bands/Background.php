<?php

namespace Thermiteplasma\Loom\Components\Bands;

use Thermiteplasma\Loom\Components\Band;

class Background extends Band
{
    public function __construct(...$props)
    {
        parent::__construct(...array_merge(['bandType' => 'background'], $props));
    }
}
