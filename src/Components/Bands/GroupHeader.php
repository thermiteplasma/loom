<?php

namespace Thermiteplasma\Loom\Components\Bands;

use Thermiteplasma\Loom\Components\Band;

class GroupHeader extends Band
{
    public function __construct(
        ?string $background = '#f0f0f0',
        ?string $padding = '3mm',
        bool $bold = true,
        ...$props,
    ) {
        parent::__construct(...array_merge(['bandType' => 'groupHeader', 'background' => $background, 'padding' => $padding, 'bold' => $bold, 'breakAfter' => false, 'keepTogether' => true], $props));
    }
}
