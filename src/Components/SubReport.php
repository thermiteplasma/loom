<?php

namespace Thermiteplasma\Loom\Components;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Thermiteplasma\Loom\Contracts\ReportDataSource;

class Subreport extends Component
{
    public Collection $items;

    public function __construct(
        public string $view,
        public array $data = [],
        public ReportDataSource|Closure|null $dataSource = null,
        public array $params = [],
        public bool $breakBefore = false,
        public bool $breakAfter = false,
    ) {
        if ($this->dataSource instanceof ReportDataSource) {
            $this->items = $this->dataSource->resolve($this->params);
        } elseif ($this->dataSource instanceof Closure) {
            $this->items = value($this->dataSource, $this->params);
        } else {
            $this->items = collect();
        }

        // Merge resolved items into data for the included view
        $this->data = array_merge($this->data, ['items' => $this->items]);
    }

    public function render()
    {
        return view('loom::components.subreport');
    }
}
