<?php

namespace Thermiteplasma\Loom\Services;

use DOMDocument;
use DOMElement;
use DOMXPath;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExcelExtractor
{
    protected Spreadsheet $spreadsheet;

    protected int $currentRow = 1;

    protected int $sheetIndex = 0;

    protected ?Worksheet $currentSheet = null;

    protected array $headers = [];

    protected ?int $dataStartRow = null;

    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet;
        $this->spreadsheet->removeSheetByIndex(0);
    }

    public function fromHtml(string $html): Spreadsheet
    {
        $doc = new DOMDocument;
        @$doc->loadHTML(
            mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'),
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        $xpath = new DOMXPath($doc);

        $bands = $xpath->query('//*[contains(@class, "band")]');

        foreach ($bands as $band) {
            if (! $band instanceof DOMElement) {
                continue;
            }

            $classes = $band->getAttribute('class');
            $this->processBand($classes, $band, $xpath);
        }

        return $this->spreadsheet;
    }

    protected function processBand(string $classes, DOMElement $band, DOMXPath $xpath): void
    {
        // Title → first sheet, header area
        if (str_contains($classes, 'band-title')) {
            $this->processTitleBand($band, $xpath);

            return;
        }

        // Group header → new sheet
        if (str_contains($classes, 'band-groupHeader')) {
            $this->processGroupHeader($band);

            return;
        }

        // Column header → header row
        if (str_contains($classes, 'band-columnHeader')) {
            $this->processColumnHeader($band, $xpath);

            return;
        }

        // Detail → data row
        if (str_contains($classes, 'band-detail')) {
            $this->processDetailRow($band, $xpath);

            return;
        }

        // Group footer → subtotal row with SUM formulas
        if (str_contains($classes, 'band-groupFooter')) {
            $this->processGroupFooter($band, $xpath);

            return;
        }

        // Summary → summary sheet
        if (str_contains($classes, 'band-summary')) {
            $this->processSummary($band, $xpath);

            return;
        }

        // page-header, page-footer, background → skip (no Excel equivalent)
    }

    protected function processTitleBand(DOMElement $band, DOMXPath $xpath): void
    {
        // Extract KPI values if present (rectangles with values)
        $sheet = $this->ensureSheet('Summary');

        $sheet->setCellValue('A1', trim(
            $xpath->query('.//h1', $band)->item(0)->textContent ?? 'Report'
        ));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);

        // Extract subtitle
        $subtitle = $xpath->query('.//p', $band)->item(0);
        if ($subtitle) {
            $sheet->setCellValue('A2', trim($subtitle->textContent));
            $sheet->getStyle('A2')->getFont()->setItalic(true);
        }

        // Extract KPI boxes
        $kpiRow = 4;
        $kpiBoxes = $xpath->query('.//*[contains(@class, "style-header-label")]', $band);
        if ($kpiBoxes->length > 0) {
            $col = 'A';
            foreach ($kpiBoxes as $label) {
                $parent = $label->parentNode;
                $spans = $xpath->query('.//span', $parent);

                $labelText = trim($label->textContent);
                $valueText = '';
                foreach ($spans as $span) {
                    $text = trim($span->textContent);
                    if ($text !== $labelText) {
                        $valueText = $text;
                        break;
                    }
                }

                $sheet->setCellValue($col.$kpiRow, $labelText);
                $sheet->getStyle($col.$kpiRow)->getFont()->setBold(true);

                $numeric = $this->parseNumeric($valueText);
                if ($numeric) {
                    $sheet->setCellValue($col.($kpiRow + 1), $numeric['value']);
                    if ($numeric['format']) {
                        $sheet->getStyle($col.($kpiRow + 1))
                            ->getNumberFormat()
                            ->setFormatCode($numeric['format']);
                    }
                } else {
                    $sheet->setCellValue($col.($kpiRow + 1), $valueText);
                }

                $col++;
            }
        }
    }

    protected function processGroupHeader(DOMElement $band): void
    {
        $groupName = trim($band->textContent);
        // Clean up: take just the first meaningful line
        $groupName = preg_replace('/\s+/', ' ', $groupName);
        $groupName = explode('  ', $groupName)[0];

        $this->currentSheet = $this->addSheet($groupName);
        $this->currentRow = 1;
        $this->dataStartRow = null;
        $this->headers = [];

        $this->currentSheet->setCellValue('A1', $groupName);
        $this->currentSheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $this->currentRow = 3;
    }

    protected function processColumnHeader(DOMElement $band, DOMXPath $xpath): void
    {
        if (! $this->currentSheet) {
            $this->currentSheet = $this->addSheet('Data');
            $this->currentRow = 1;
        }

        $this->headers = $this->extractColumns($band, $xpath);

        $col = 'A';
        foreach ($this->headers as $header) {
            $cell = $col.$this->currentRow;
            $this->currentSheet->setCellValue($cell, $header['label']);
            $this->currentSheet->getStyle($cell)->getFont()->setBold(true);
            $this->currentSheet->getStyle($cell)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('E8EEF3');

            if ($header['align'] === 'right') {
                $this->currentSheet->getStyle($col.'1:'.$col.'9999')
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            } elseif ($header['align'] === 'center') {
                $this->currentSheet->getStyle($col.'1:'.$col.'9999')
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }

            if ($header['flex']) {
                $this->currentSheet->getColumnDimension($col)
                    ->setWidth(max((int) $header['flex'] * 12, 10));
            } else {
                $this->currentSheet->getColumnDimension($col)->setAutoSize(true);
            }

            $col++;
        }

        $this->currentRow++;
        $this->dataStartRow = $this->currentRow;
    }

    protected function processDetailRow(DOMElement $band, DOMXPath $xpath): void
    {
        if (! $this->currentSheet) {
            return;
        }

        if (! $this->dataStartRow) {
            $this->dataStartRow = $this->currentRow;
        }

        $values = $this->extractCellValues($band, $xpath);
        $col = 'A';

        foreach ($values as $value) {
            $cell = $col.$this->currentRow;
            $numeric = $this->parseNumeric($value);

            if ($numeric !== null) {
                $this->currentSheet->setCellValue($cell, $numeric['value']);
                if ($numeric['format']) {
                    $this->currentSheet->getStyle($cell)
                        ->getNumberFormat()
                        ->setFormatCode($numeric['format']);
                }
            } else {
                $this->currentSheet->setCellValue($cell, $value);
            }

            $col++;
        }

        // Alternate row shading
        if (($this->currentRow - ($this->dataStartRow ?? 1)) % 2 === 1) {
            $maxCol = chr(ord('A') + max(count($values) - 1, 0));
            $this->currentSheet->getStyle("A{$this->currentRow}:{$maxCol}{$this->currentRow}")
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('FAFAFA');
        }

        $this->currentRow++;
    }

    protected function processGroupFooter(DOMElement $band, DOMXPath $xpath): void
    {
        if (! $this->currentSheet || ! $this->dataStartRow) {
            return;
        }

        $values = $this->extractCellValues($band, $xpath);
        $col = 'A';
        $lastDataRow = $this->currentRow - 1;

        foreach ($values as $value) {
            $cell = $col.$this->currentRow;
            $numeric = $this->parseNumeric($value);

            // Insert SUM formula for numeric columns
            if ($numeric !== null && $this->dataStartRow <= $lastDataRow) {
                $this->currentSheet->setCellValue(
                    $cell,
                    "=SUM({$col}{$this->dataStartRow}:{$col}{$lastDataRow})"
                );
                if ($numeric['format']) {
                    $this->currentSheet->getStyle($cell)
                        ->getNumberFormat()
                        ->setFormatCode($numeric['format']);
                }
            } else {
                $this->currentSheet->setCellValue($cell, $value);
            }

            $this->currentSheet->getStyle($cell)->getFont()->setBold(true);
            $col++;
        }

        // Top border on totals row
        $maxCol = chr(ord('A') + max(count($values) - 1, 0));
        $this->currentSheet->getStyle("A{$this->currentRow}:{$maxCol}{$this->currentRow}")
            ->getBorders()->getTop()
            ->setBorderStyle(Border::BORDER_THIN);

        $this->currentRow++;
        $this->dataStartRow = null; // reset for next group within same sheet
    }

    protected function processSummary(DOMElement $band, DOMXPath $xpath): void
    {
        // Add summary data to the Summary sheet
        $sheet = $this->ensureSheet('Summary');

        // Find last used row on summary sheet
        $row = $sheet->getHighestRow() + 2;

        $sheet->setCellValue("A{$row}", 'Grand Total');
        $sheet->getStyle("A{$row}")->getFont()->setBold(true)->setSize(12);

        $values = $this->extractCellValues($band, $xpath);
        $col = 'B';
        foreach ($values as $value) {
            $cell = $col.$row;
            $numeric = $this->parseNumeric($value);
            if ($numeric) {
                $sheet->setCellValue($cell, $numeric['value']);
                if ($numeric['format']) {
                    $sheet->getStyle($cell)
                        ->getNumberFormat()
                        ->setFormatCode($numeric['format']);
                }
            } else {
                $sheet->setCellValue($cell, $value);
            }
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $col++;
        }
    }

    // ----------------------------------------------------------------
    // Extraction helpers
    // ----------------------------------------------------------------

    protected function extractColumns(DOMElement $band, DOMXPath $xpath): array
    {
        $columns = [];

        // Look for flex row > column pattern
        $flexRow = $xpath->query('.//*[contains(@style, "display: flex")]', $band);
        $container = $flexRow->length > 0 ? $flexRow->item(0) : $band;

        $children = $xpath->query('./div', $container);

        foreach ($children as $child) {
            if (!$child instanceof DOMElement) continue;
            $style = $child->getAttribute('style');
            $columns[] = [
                'label' => trim($child->textContent),
                'align' => $this->extractAlign($style),
                'flex' => $this->extractFlex($style),
            ];
        }

        return $columns;
    }

    protected function extractCellValues(DOMElement $band, DOMXPath $xpath): array
    {
        $values = [];

        $flexRow = $xpath->query('.//*[contains(@style, "display: flex")]', $band);
        $container = $flexRow->length > 0 ? $flexRow->item(0) : $band;

        $cells = $xpath->query('./div', $container);

        foreach ($cells as $cell) {
            // Prefer span content (field/static-text)
            $spans = $xpath->query('.//span', $cell);
            if ($spans->length > 0) {
                $parts = [];
                foreach ($spans as $span) {
                    $text = trim($span->textContent);
                    if ($text !== '') {
                        $parts[] = $text;
                    }
                }
                $values[] = implode(' ', $parts);
            } else {
                $values[] = trim($cell->textContent);
            }
        }

        return $values;
    }

    protected function parseNumeric(string $value): ?array
    {
        $clean = trim($value);

        // Currency: $1,234.56
        if (preg_match('/^\$[\d,]+\.?\d*$/', $clean)) {
            return [
                'value' => (float) str_replace(['$', ','], '', $clean),
                'format' => '$#,##0.00',
            ];
        }

        // Percentage: 45.67%
        if (preg_match('/^[\d.]+%$/', $clean)) {
            return [
                'value' => (float) str_replace('%', '', $clean) / 100,
                'format' => '0.00%',
            ];
        }

        // Integer with commas: 1,234
        if (preg_match('/^[\d,]+$/', $clean) && str_contains($clean, ',')) {
            return [
                'value' => (int) str_replace(',', '', $clean),
                'format' => '#,##0',
            ];
        }

        // Plain number
        if (is_numeric($clean)) {
            return [
                'value' => (float) $clean,
                'format' => null,
            ];
        }

        return null;
    }

    protected function extractAlign(string $style): string
    {
        if (str_contains($style, 'text-align: right')) {
            return 'right';
        }
        if (str_contains($style, 'text-align: center')) {
            return 'center';
        }

        return 'left';
    }

    protected function extractFlex(string $style): ?string
    {
        if (preg_match('/flex:\s*(\d+)/', $style, $m)) {
            return $m[1];
        }

        return null;
    }

    protected function addSheet(string $name): Worksheet
    {
        $name = substr(
            preg_replace('/[\\\\\/\*\?\[\]:]+/', '', trim($name)),
            0, 31
        );

        // Ensure unique name
        $existing = [];
        for ($i = 0; $i < $this->spreadsheet->getSheetCount(); $i++) {
            $existing[] = $this->spreadsheet->getSheet($i)->getTitle();
        }
        $original = $name;
        $counter = 1;
        while (in_array($name, $existing)) {
            $name = substr($original, 0, 28).' ('.$counter++.')';
        }

        $sheet = $this->spreadsheet->createSheet($this->sheetIndex++);
        $sheet->setTitle($name);

        return $sheet;
    }

    protected function ensureSheet(string $name): Worksheet
    {
        // Return existing sheet or create new
        for ($i = 0; $i < $this->spreadsheet->getSheetCount(); $i++) {
            if ($this->spreadsheet->getSheet($i)->getTitle() === $name) {
                return $this->spreadsheet->getSheet($i);
            }
        }

        return $this->addSheet($name);
    }
}
