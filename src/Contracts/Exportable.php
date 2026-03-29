<?php 

namespace Thermiteplasma\Loom\Contracts;

interface Exportable
{
    /**
     * Return an array of ReportSheet definitions for Excel export.
     *
     * @return \Thermiteplasma\Loom\Support\ReportSheet[]
     */
    public function sheets(): array;
}