<?php

namespace Thermiteplasma\Loom\DataSources;

use Closure;
use Illuminate\Support\Collection;
use Thermiteplasma\Loom\Contracts\ReportDataSource;

class CallbackDataSource implements ReportDataSource
{
    /** @param Closure(array): Collection $callback */
    public function __construct(
        protected Closure $callback,
    ) {}

    public function resolve(array $params = []): Collection
    {
        return value($this->callback, $params);
    }
}
