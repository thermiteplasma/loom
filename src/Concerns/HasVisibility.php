<?php

namespace Thermiteplasma\Loom\Concerns;

trait HasVisibility
{
    public bool $visible = true;

    public bool $removeWhenBlank = false;

    public bool $printRepeated = true;
}
