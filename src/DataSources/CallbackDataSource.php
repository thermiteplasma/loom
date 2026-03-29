<?php 

namespace Thermiteplasma\Loom\DataSources;

use Thermiteplasma\Loom\Contracts\ReportDataSource;
use Closure;
use Illuminate\Support\Collection;

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