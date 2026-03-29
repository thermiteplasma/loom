<?php

namespace Thermiteplasma\Loom\Contracts;

use Thermiteplasma\Loom\Support\ReportSheet;

interface Exportable
{
    /**
     * Return an array of ReportSheet definitions for Excel export.
     *
     * @return ReportSheet[]
     */
    public function sheets(): array;
}
