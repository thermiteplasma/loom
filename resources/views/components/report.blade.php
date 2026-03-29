<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page {
            size: {{ $pageSize() }};
            margin: {{ $pageMargin() }};

            @top-center { content: element(runningPageHeader); }
            @bottom-center { content: element(runningPageFooter); }
        }

        @page :first {
            @top-center { content: none; }
        }

        .running-page-header {
            position: running(runningPageHeader);
            width: 100%;
        }
        .running-page-footer {
            position: running(runningPageFooter);
            width: 100%;
        }

        * { box-sizing: border-box; }

        body {
            font-family: {{ $fontFamily }};
            font-size: {{ $fontSize }};
            color: {{ $color }};
            line-height: {{ $lineHeight }};
            margin: 0;
            padding: 0;
        }

        table { border-collapse: collapse; }
        table thead { display: table-header-group; }
        table tfoot { display: table-footer-group; }

        /* Utility classes */
        .text-left { text-align: left; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-justify { text-align: justify; }
        .text-bold { font-weight: bold; }
        .text-italic { font-style: italic; }
        .text-underline { text-decoration: underline; }
        .text-muted { color: #999; }
        .text-small { font-size: 7pt; }

        /* Striped tables */
        table.striped tbody tr:nth-child(even) td {
            background: var(--stripe-color, #f9f9f9);
        }

        /* Page counters (WeasyPrint) */
        .page-number::before { content: counter(page); }
        .total-pages::before { content: counter(pages); }
    </style>
    {{ $styles ?? '' }}
</head>
<body>
    {{ $slot }}
</body>
</html>