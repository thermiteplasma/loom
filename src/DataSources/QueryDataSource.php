<?php

namespace Thermiteplasma\Loom\DataSources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Thermiteplasma\Loom\Contracts\ReportDataSource;

class QueryDataSource implements ReportDataSource
{
    public function __construct(
        protected Builder $query,
    ) {}

    public function resolve(array $params = []): Collection
    {
        $query = clone $this->query;

        foreach ($params as $key => $value) {
            $query->where($key, $value);
        }

        return $query->get();
    }
}
