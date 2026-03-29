<?php

namespace Thermiteplasma\Loom\Contracts;

use Illuminate\Support\Collection;

interface ReportDataSource
{
    public function resolve(array $params = []): Collection;
}