<?php

namespace Thermiteplasma\Loom\Components;

use Illuminate\Support\Collection;
use Thermiteplasma\Loom\Contracts\ReportDataSource;
use Closure;

class DataList extends Band
{
    public Collection $items;

    public function __construct(
        public ReportDataSource|Closure|Collection $dataSource,
        public ?string $view = null,
        public string $printOrder = 'vertical',
        public array $params = [],
        ...$bandProps,
    ) {
        parent::__construct(...['bandType' => 'list', ...$bandProps]);

        $this->items = match (true) {
            $this->dataSource instanceof ReportDataSource => $this->dataSource->resolve($this->params),
            $this->dataSource instanceof Closure => value($this->dataSource, $this->params),
            $this->dataSource instanceof Collection => $this->dataSource,
        };
    }

    public function render()
    {
        return view('loom::components.data-list');
    }
}