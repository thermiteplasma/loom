<?php

namespace Thermiteplasma\Loom\Concerns;

trait BuildsStyles
{
    protected function buildStyleString(array $declarations): string
    {
        return collect($declarations)
            ->filter(fn ($v) => $v !== null && $v !== false && $v !== '')
            ->map(fn ($v, $k) => "{$k}: {$v}")
            ->implode('; ');
    }
}
