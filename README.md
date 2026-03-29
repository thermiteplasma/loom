# Loom

A Jasper Reports-inspired PDF reporting engine for Laravel using Blade components and WeasyPrint.

Write reports as declarative Blade templates using property-driven components. Get PDF output via WeasyPrint and Excel export by introspecting the same HTML — one template, multiple formats.

## Requirements

- PHP 8.2+
- Laravel 11 or 12
- [WeasyPrint](https://weasyprint.org/) installed on your system
- Optional: `phpoffice/phpspreadsheet` for Excel export

## Installation

```bash
composer require agt/loom
```

Publish the config:

```bash
php artisan vendor:publish --tag=loom-config
```

Optionally publish the views to customise base templates:

```bash
php artisan vendor:publish --tag=loom-views
```

### Installing WeasyPrint

```bash
# macOS
brew install weasyprint

# Ubuntu/Debian
sudo apt install weasyprint

# pip
pip install weasyprint
```

## Quick Start

### 1. Create a report template

```blade
{{-- resources/views/reports/sales.blade.php --}}

<x-loom-report size="A4" orientation="landscape" margin="12mm">

    <x-loom-page-header borderBottom borderColor="#1a5276">
        <x-loom-row justify="space-between" items="center">
            <x-loom-column>
                <x-loom-field bold color="#1a5276">Sales Report</x-loom-field>
            </x-loom-column>
            <x-loom-column align="right">
                <x-loom-page-number showTotal />
            </x-loom-column>
        </x-loom-row>
    </x-loom-page-header>

    <x-loom-page-footer borderTop>
        <x-loom-row justify="space-between">
            <x-loom-column>
                <x-loom-static-text>Confidential</x-loom-static-text>
            </x-loom-column>
            <x-loom-column align="right">
                <x-loom-current-date />
            </x-loom-column>
        </x-loom-row>
    </x-loom-page-footer>

    <x-loom-title :breakAfter="false" align="left">
        <x-loom-field tag="h1" fontSize="18pt" color="#1a5276">
            Monthly Sales
        </x-loom-field>
    </x-loom-title>

    @foreach($regions as $region => $rows)
        <x-loom-group-header background="#1a5276" color="white">
            <x-loom-field bold>{{ $region }}</x-loom-field>
        </x-loom-group-header>

        <x-loom-column-header>
            <x-loom-row>
                <x-loom-column flex="2">Rep</x-loom-column>
                <x-loom-column flex="1" align="right">Revenue</x-loom-column>
            </x-loom-row>
        </x-loom-column-header>

        @foreach($rows as $row)
            <x-loom-detail borderBottom="dotted" borderColor="#eee">
                <x-loom-row>
                    <x-loom-column flex="2">{{ $row->name }}</x-loom-column>
                    <x-loom-column flex="1" align="right">
                        <x-loom-field bold>${{ number_format($row->revenue, 2) }}</x-loom-field>
                    </x-loom-column>
                </x-loom-row>
            </x-loom-detail>
        @endforeach

        <x-loom-group-footer background="#f0f0f0">
            <x-loom-row justify="space-between">
                <x-loom-field bold>{{ $region }} Total</x-loom-field>
                <x-loom-field bold>${{ number_format($rows->sum('revenue'), 2) }}</x-loom-field>
            </x-loom-row>
        </x-loom-group-footer>
    @endforeach

    <x-loom-summary background="#1a5276" padding="5mm">
        <x-loom-field bold fontSize="14pt" color="white">
            Grand Total: ${{ number_format($regions->flatten()->sum('revenue'), 2) }}
        </x-loom-field>
    </x-loom-summary>

</x-loom-report>
```

### 2. Generate from a controller

```php
use Thermiteplasma\Loom\Services\ReportService;

class ReportController extends Controller
{
    public function sales(Request $request, ReportService $loom)
    {
        $regions = // ... your query

        $data = compact('regions');

        return match ($request->input('format', 'pdf')) {
            'pdf'  => $loom->pdf('reports.sales', $data, 'sales-report.pdf'),
            'xlsx' => $loom->excel('reports.sales', $data, 'sales-report.xlsx'),
            'html' => response($loom->html('reports.sales', $data)),
        };
    }
}
```

## Components Reference

### Report Wrapper

| Component | Description |
|-----------|-------------|
| `<x-loom-report>` | Root wrapper. Sets page size, margins, base typography. |

**Props:** `size` (A4, Letter, etc), `orientation` (portrait/landscape), `margin`, `marginTop/Bottom/Left/Right`, `fontFamily`, `fontSize`, `color`, `lineHeight`

### Bands

All bands share these props: `padding`, `margin`, `background`, `color`, `fontSize`, `fontFamily`, `bold`, `italic`, `underline`, `align`, `textTransform`, `border`, `borderTop/Bottom/Left/Right`, `borderStyle`, `borderWidth`, `borderColor`, `borderRadius`, `breakBefore`, `breakAfter`, `keepTogether`, `visible`, `opacity`, `width`, `height`

| Component | Default Behaviour |
|-----------|-------------------|
| `<x-loom-title>` | Page break after, centered, 8mm padding |
| `<x-loom-page-header>` | Repeats on every page via CSS running elements |
| `<x-loom-page-footer>` | Repeats on every page, 7pt muted text |
| `<x-loom-column-header>` | Bold, 8pt, light background, bottom border |
| `<x-loom-column-footer>` | Top border |
| `<x-loom-group-header>` | Light background, bold, avoids break from first detail |
| `<x-loom-group-footer>` | Bold, top border |
| `<x-loom-detail>` | Keeps together, minimal padding |
| `<x-loom-summary>` | Page break before, bold, 5mm padding |
| `<x-loom-last-page-footer>` | Only on final page |
| `<x-loom-no-data>` | Centered, muted, shown when no data |
| `<x-loom-background>` | Background watermark layer |

### Elements

| Component | Description | Key Props |
|-----------|-------------|-----------|
| `<x-loom-field>` | Dynamic text field | `tag` (span/div/p), `format`, `stretchWithOverflow`, + all typography/box/border props |
| `<x-loom-static-text>` | Static label (inherits Field) | Same as field, defaults to muted colour |
| `<x-loom-image>` | Image element | `src`, `alt`, `fit` (contain/cover/fill), `align` |
| `<x-loom-line>` | Horizontal/vertical rule | `direction`, `lineStyle`, `lineWidth`, `lineColor` |
| `<x-loom-rectangle>` | Box shape | All box/border/background props |
| `<x-loom-ellipse>` | Circle/oval | Same as rectangle with border-radius: 50% |
| `<x-loom-frame>` | Grouping container | `display` (block/flex/grid), `gap`, `justify`, `items`, `direction`, `wrap` |
| `<x-loom-page-break>` | Force page break | — |
| `<x-loom-row>` | Flex row layout | `gap`, `justify`, `items`, `wrap` |
| `<x-loom-column>` | Column within a row | `flex`, `basis`, `width`, `align` |
| `<x-loom-table>` | Table wrapper | `striped`, `stripeColor`, `repeatHeader` |
| `<x-loom-table-column>` | Column definition | `header`, `width`, `align` |
| `<x-loom-subreport>` | Embedded sub-report | `view`, `data`, `dataSource`, `params` |
| `<x-loom-data-list>` | List with independent data | `dataSource`, `view`, `printOrder` (vertical/horizontal) |

### Utility Elements

| Component | Description | Key Props |
|-----------|-------------|-----------|
| `<x-loom-page-number>` | Current page number | `prefix`, `showTotal`, `separator` |
| `<x-loom-total-pages>` | Total page count | — |
| `<x-loom-current-date>` | Timestamp | `format` (PHP date format) |
| `<x-loom-style>` | Named reusable CSS class | `name`, all typography/border/background props |

### Named Styles

Define reusable styles and apply them with `class="style-{name}"`:

```blade
<x-loom-style name="highlight" bold color="#e74c3c" fontSize="11pt" />

<x-loom-field class="style-highlight">Important!</x-loom-field>
```

## Property-Driven Styling

Every component accepts Jasper-like styling properties. No CSS needed in your reports:

```blade
{{-- Borders --}}
<x-loom-detail border>                              {{-- all sides, solid, 0.5pt, #ccc --}}
<x-loom-detail borderBottom>                         {{-- bottom only --}}
<x-loom-detail borderBottom="dotted">                {{-- bottom, dotted --}}
<x-loom-detail border borderWidth="1pt" borderColor="#000">  {{-- custom --}}

{{-- Typography --}}
<x-loom-field bold italic underline>text</x-loom-field>
<x-loom-field fontSize="12pt" color="#1a5276" align="right" textTransform="uppercase">

{{-- Box model --}}
<x-loom-detail padding="3mm 5mm" marginBottom="2mm" background="#f9f9f9">

{{-- Break control --}}
<x-loom-group-header :keepTogether="true">          {{-- won't split across pages --}}
<x-loom-title breakAfter>                            {{-- page break after --}}
<x-loom-summary breakBefore>                         {{-- page break before --}}
```

## Subreports & Independent Data Sources

```php
use Thermiteplasma\Loom\DataSources\QueryDataSource;
use Thermiteplasma\Loom\DataSources\CallbackDataSource;
```

```blade
{{-- Closure-based --}}
<x-loom-subreport
    view="reports.partials.contacts"
    :dataSource="fn($p) => Contact::where('venue_id', $p['id'])->get()"
    :params="['id' => $venue->id]"
/>

{{-- Query-based --}}
<x-loom-subreport
    view="reports.partials.machines"
    :dataSource="new QueryDataSource(Machine::where('active', true))"
/>

{{-- DataList with inline rendering --}}
<x-loom-data-list :dataSource="$venue->orders" padding="2mm">
    @foreach($component->items as $order)
        <x-loom-detail>
            <x-loom-field>{{ $order->reference }}</x-loom-field>
        </x-loom-detail>
    @endforeach
</x-loom-data-list>
```

## Excel Export

### Automatic (HTML introspection)

The same report template produces Excel output. Loom reads the HTML band structure and maps it:

| Loom Band | Excel Output |
|-----------|-------------|
| `group-header` | New sheet |
| `column-header` | Bold header row with column widths |
| `detail` | Data rows with auto-detected number formats |
| `group-footer` | Bold row with SUM formulas |
| `summary` | Separate Summary sheet |
| `page-header/footer` | Skipped |

Currency (`$1,234.56`), percentages (`45.67%`), and plain numbers are automatically detected and formatted.

```php
$loom->excel('reports.sales', $data, 'sales.xlsx');
```

### Explicit (Exportable interface)

For full control over the Excel output:

```php
use Thermiteplasma\Loom\Contracts\Exportable;
use Thermiteplasma\Loom\Support\ReportSheet;

class SalesReport implements Exportable
{
    public function sheets(): array
    {
        return [
            new ReportSheet(
                title: 'Q1 Sales',
                headers: ['Rep', 'Orders', 'Revenue'],
                rows: $this->query()->map(fn ($r) => [$r->name, $r->orders, $r->revenue]),
                columnFormats: ['C' => '$#,##0.00'],
                totalColumns: ['B', 'C'],
            ),
        ];
    }
}

// In controller:
$loom->excelFromSheets(new SalesReport(), 'sales.xlsx');
```

## Configuration

```php
// config/loom.php
return [
    'binary' => env('LOOM_BINARY', 'weasyprint'),
    'timeout' => env('LOOM_TIMEOUT', 120),
    'options' => [
        '--encoding' => 'utf-8',
        '--presentational-hints' => null,
    ],
    'defaults' => [
        'size' => 'A4',
        'orientation' => 'portrait',
        'margin' => '15mm',
        'font_family' => 'Helvetica Neue, Helvetica, Arial, sans-serif',
        'font_size' => '9pt',
        'color' => '#333333',
        'line_height' => '1.4',
    ],
    'storage_path' => storage_path('app/reports'),
];
```

## ReportService API

```php
use Thermiteplasma\Loom\Services\ReportService;

$loom = app(ReportService::class);

// Render to HTML string
$html = $loom->html('reports.sales', $data);

// Render to PDF bytes
$bytes = $loom->render('reports.sales', $data);

// Return inline PDF response
return $loom->pdf('reports.sales', $data, 'filename.pdf');

// Return download PDF response
return $loom->pdf('reports.sales', $data, 'filename.pdf', download: true);

// Save PDF to disk
$path = $loom->save('reports.sales', $data, storage_path('reports/sales.pdf'));

// Excel via HTML introspection
return $loom->excel('reports.sales', $data, 'filename.xlsx');

// Excel via explicit sheets
return $loom->excelFromSheets($exportableReport, 'filename.xlsx');
```

## Jasper Reports Mapping

| Jasper Concept | Loom Equivalent |
|---------------|----------------|
| `<jasperReport>` | `<x-loom-report>` |
| `<title>` | `<x-loom-title>` |
| `<pageHeader>` | `<x-loom-page-header>` |
| `<pageFooter>` | `<x-loom-page-footer>` |
| `<columnHeader>` | `<x-loom-column-header>` |
| `<columnFooter>` | `<x-loom-column-footer>` |
| `<groupHeader>` | `<x-loom-group-header>` |
| `<groupFooter>` | `<x-loom-group-footer>` |
| `<detail>` | `<x-loom-detail>` |
| `<summary>` | `<x-loom-summary>` |
| `<lastPageFooter>` | `<x-loom-last-page-footer>` |
| `<noData>` | `<x-loom-no-data>` |
| `<background>` | `<x-loom-background>` |
| `<staticText>` | `<x-loom-static-text>` |
| `<textField>` | `<x-loom-field>` |
| `<image>` | `<x-loom-image>` |
| `<line>` | `<x-loom-line>` |
| `<rectangle>` | `<x-loom-rectangle>` |
| `<ellipse>` | `<x-loom-ellipse>` |
| `<frame>` | `<x-loom-frame>` |
| `<break>` | `<x-loom-page-break>` |
| `<subreport>` | `<x-loom-subreport>` |
| `<list>` | `<x-loom-data-list>` |
| `<style>` | `<x-loom-style>` |
| `reportElement` props | Trait-based props on every component |
| `$V{PAGE_NUMBER}` | `<x-loom-page-number>` |
| `$V{PAGE_COUNT}` | `<x-loom-total-pages>` |

## License

MIT