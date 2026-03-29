<?php

use Thermiteplasma\Loom\Components\Band;

// --- classes() ---

it('generates band and band-type classes', function () {
    $band = new Band(bandType: 'detail');
    expect($band->classes())->toBe('band band-detail');
});

it('adds running-page-header class for pageHeader type', function () {
    $band = new Band(bandType: 'pageHeader');
    expect($band->classes())->toBe('band band-pageHeader running-page-header');
});

it('adds running-page-footer class for pageFooter type', function () {
    $band = new Band(bandType: 'pageFooter');
    expect($band->classes())->toBe('band band-pageFooter running-page-footer');
});

it('does not add running classes for other band types', function () {
    $band = new Band(bandType: 'summary');
    expect($band->classes())->not->toContain('running-');
});

// --- styles() box model ---

it('includes width in styles when set', function () {
    $band = new Band(bandType: 'detail', width: '100mm');
    expect($band->styles())->toContain('width: 100mm');
});

it('omits width when not set', function () {
    $band = new Band(bandType: 'detail');
    expect($band->styles())->not->toContain('width:');
});

// --- styles() page break ---

it('includes break-inside avoid when keepTogether is true', function () {
    $band = new Band(bandType: 'detail', keepTogether: true);
    expect($band->styles())->toContain('break-inside: avoid');
});

it('omits break-inside when keepTogether is false', function () {
    $band = new Band(bandType: 'detail', keepTogether: false);
    expect($band->styles())->not->toContain('break-inside');
});

it('includes break-before page when breakBefore is true', function () {
    $band = new Band(bandType: 'summary', breakBefore: true);
    expect($band->styles())->toContain('break-before: page');
});

it('includes break-after page when breakAfter is true', function () {
    $band = new Band(bandType: 'detail', breakAfter: true);
    expect($band->styles())->toContain('break-after: page');
});

it('includes break-after avoid for groupHeader when breakAfter is false', function () {
    $band = new Band(bandType: 'groupHeader', breakAfter: false);
    expect($band->styles())->toContain('break-after: avoid');
});

it('does not include break-after avoid for non-groupHeader bands', function () {
    $band = new Band(bandType: 'detail', breakAfter: false);
    expect($band->styles())->not->toContain('break-after');
});
