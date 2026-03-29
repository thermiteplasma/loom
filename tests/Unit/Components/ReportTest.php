<?php

use Thermiteplasma\Loom\Components\Report;

it('returns size unchanged for portrait orientation', function () {
    $report = new Report(size: 'A4', orientation: 'portrait');
    expect($report->pageSize())->toBe('A4');
});

it('appends landscape to the size string', function () {
    $report = new Report(size: 'A4', orientation: 'landscape');
    expect($report->pageSize())->toBe('A4 landscape');
});

it('does not duplicate landscape when already present in size', function () {
    $report = new Report(size: 'A4 landscape', orientation: 'landscape');
    expect($report->pageSize())->toBe('A4 landscape');
});

it('returns the uniform margin when no individual margins are set', function () {
    $report = new Report(margin: '20mm');
    expect($report->pageMargin())->toBe('20mm');
});

it('returns four-value margin when any individual margin is set', function () {
    // top right bottom left
    $report = new Report(margin: '15mm', marginTop: '10mm', marginBottom: '20mm');
    expect($report->pageMargin())->toBe('10mm 15mm 20mm 15mm');
});

it('falls back to the uniform margin for unspecified individual sides', function () {
    $report = new Report(margin: '15mm', marginLeft: '5mm');
    expect($report->pageMargin())->toBe('15mm 15mm 15mm 5mm');
});

it('supports all four individual margins', function () {
    $report = new Report(
        margin: '0',
        marginTop: '10mm',
        marginRight: '20mm',
        marginBottom: '30mm',
        marginLeft: '40mm',
    );
    expect($report->pageMargin())->toBe('10mm 20mm 30mm 40mm');
});
