<?php 

namespace Thermiteplasma\Loom\DataSources;

use Thermiteplasma\Loom\Contracts\ReportDataSource;
use Illuminate\Support\Collection;

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
