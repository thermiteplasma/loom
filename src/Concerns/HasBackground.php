<?php 

namespace Thermiteplasma\Loom\Concerns;

trait HasBackground
{
    public ?string $background = null;
    public ?string $opacity = null;

    protected function backgroundStyles(): array
    {
        return [
            'background' => $this->background,
            'opacity' => $this->opacity,
        ];
    }
}