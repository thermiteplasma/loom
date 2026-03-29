<?php

namespace Thermiteplasma\Loom\Support;

use Illuminate\Support\Collection;

class ReportSheet
{
    public function __construct(
        public string $title,
        public array $headers,
        public Collection $rows,
        public array $columnFormats = [],
        public ?array $totalColumns = null,
        public ?array $columnWidths = null,
    ) {}
}
