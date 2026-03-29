<?php

namespace Thermiteplasma\Loom\DataSources;

use Illuminate\Support\Collection;
use Thermiteplasma\Loom\Contracts\ReportDataSource;

class CollectionDataSource implements ReportDataSource
{
    public function __construct(
        protected Collection $items,
    ) {}

    public function resolve(array $params = []): Collection
    {
        return $this->items;
    }
}
