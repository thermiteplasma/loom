<?php

namespace Thermiteplasma\Loom\Services;

use Illuminate\Support\Facades\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Thermiteplasma\Loom\Contracts\Exportable;

class ReportService
{
    public function __construct(
        protected string $binary = 'weasyprint',
        protected int $timeout = 120,
        protected array $options = [],
    ) {}

    /**
     * Render a report view to HTML string.
     */
    public function html(string $view, array $data = []): string
    {
        return View::make($view, $data)->render();
    }

    /**
     * Render a report view to PDF bytes.
     */
    public function render(string $view, array $data = []): string
    {
        return $this->htmlToPdf($this->html($view, $data));
    }

    /**
     * Convert raw HTML string to PDF bytes.
     */
    public function htmlToPdf(string $html): string
    {
        $command = [$this->binary];

        $options = array_merge([
            '--encoding' => 'utf-8',
            '--presentational-hints' => null,
        ], $this->options);

        foreach ($options as $key => $value) {
            $command[] = $key;
            if ($value !== null) {
                $command[] = $value;
            }
        }

        $command[] = '-'; // stdin
        $command[] = '-'; // stdout

        $process = new Process($command, null, null, $html, $this->timeout);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process->getOutput();
    }

    /**
     * Return an inline PDF response.
     */
    public function pdf(string $view, array $data = [], ?string $filename = null, bool $download = false)
    {
        $pdf = $this->render($view, $data);
        $filename ??= 'report-'.now()->format('Ymd-His').'.pdf';
        $disposition = $download ? 'attachment' : 'inline';

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "{$disposition}; filename=\"{$filename}\"",
            'Content-Length' => strlen($pdf),
        ]);
    }

    /**
     * Save a report PDF to disk.
     */
    public function save(string $view, array $data = [], ?string $path = null): string
    {
        $pdf = $this->render($view, $data);
        $path ??= config('loom.storage_path').'/report-'.now()->format('Ymd-His').'.pdf';

        $dir = dirname($path);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        file_put_contents($path, $pdf);

        return $path;
    }

    /**
     * Export a report to Excel by introspecting the rendered HTML.
     * Requires phpoffice/phpspreadsheet.
     */
    public function excel(string $view, array $data = [], ?string $filename = null)
    {
        $html = $this->html($view, $data);

        $extractor = new ExcelExtractor;
        $spreadsheet = $extractor->fromHtml($html);

        $filename ??= 'report-'.now()->format('Ymd-His').'.xlsx';

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(
            fn () => $writer->save('php://output'),
            $filename,
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        );
    }

    /**
     * Export using an Exportable report class with explicit sheet definitions.
     */
    public function excelFromSheets(Exportable $report, ?string $filename = null)
    {
        $spreadsheet = new Spreadsheet;
        $spreadsheet->removeSheetByIndex(0);

        foreach ($report->sheets() as $index => $sheet) {
            $ws = $spreadsheet->createSheet($index);
            $ws->setTitle(substr($sheet->title, 0, 31));

            // Headers
            $col = 'A';
            foreach ($sheet->headers as $header) {
                $ws->setCellValue($col.'1', $header);
                $ws->getStyle($col.'1')->getFont()->setBold(true);
                $ws->getColumnDimension($col)->setAutoSize(true);
                $col++;
            }

            // Data rows
            $rowNum = 2;
            foreach ($sheet->rows as $row) {
                $col = 'A';
                foreach ($row as $value) {
                    $ws->setCellValue($col.$rowNum, $value);

                    if (isset($sheet->columnFormats[$col])) {
                        $ws->getStyle($col.$rowNum)
                            ->getNumberFormat()
                            ->setFormatCode($sheet->columnFormats[$col]);
                    }

                    $col++;
                }
                $rowNum++;
            }

            // Totals row
            if ($sheet->totalColumns) {
                $lastDataRow = $rowNum - 1;
                $col = 'A';
                $maxCol = chr(ord('A') + count($sheet->headers) - 1);

                while ($col <= $maxCol) {
                    if (in_array($col, $sheet->totalColumns)) {
                        $ws->setCellValue(
                            $col.$rowNum,
                            "=SUM({$col}2:{$col}{$lastDataRow})"
                        );
                        if (isset($sheet->columnFormats[$col])) {
                            $ws->getStyle($col.$rowNum)
                                ->getNumberFormat()
                                ->setFormatCode($sheet->columnFormats[$col]);
                        }
                    }
                    $ws->getStyle($col.$rowNum)->getFont()->setBold(true);
                    $col++;
                }
            }
        }

        $filename ??= 'report-'.now()->format('Ymd-His').'.xlsx';
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(
            fn () => $writer->save('php://output'),
            $filename,
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        );
    }
}
