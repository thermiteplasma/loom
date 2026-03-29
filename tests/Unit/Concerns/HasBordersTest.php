<?php

use Thermiteplasma\Loom\Concerns\{BuildsStyles, HasBorders};

beforeEach(function () {
    $this->subject = new class {
        use BuildsStyles, HasBorders;

        public function borders(): array
        {
            return $this->borderStyles();
        }
    };
});

it('returns no styles when all borders are false', function () {
    expect($this->subject->borders())->toBeEmpty();
});

it('generates shorthand border when border is true', function () {
    $this->subject->border = true;
    expect($this->subject->borders()['border'])->toBe('0.5pt solid #cccccc');
});

it('uses a custom style string for the shorthand border', function () {
    $this->subject->border = 'dashed';
    expect($this->subject->borders()['border'])->toBe('0.5pt dashed #cccccc');
});

it('respects custom borderWidth, borderStyle, and borderColor', function () {
    $this->subject->border = true;
    $this->subject->borderWidth = '2pt';
    $this->subject->borderStyle = 'dashed';
    $this->subject->borderColor = '#000000';
    expect($this->subject->borders()['border'])->toBe('2pt dashed #000000');
});

it('generates individual side borders when only some sides are set', function () {
    $this->subject->borderTop = true;
    $this->subject->borderBottom = true;
    $styles = $this->subject->borders();

    expect($styles)->toHaveKey('border-top');
    expect($styles)->toHaveKey('border-bottom');
    expect($styles)->not->toHaveKey('border-left');
    expect($styles)->not->toHaveKey('border-right');
    expect($styles)->not->toHaveKey('border');
});

it('uses a custom style string for individual sides', function () {
    $this->subject->borderLeft = 'dotted';
    expect($this->subject->borders()['border-left'])->toBe('0.5pt dotted #cccccc');
});

it('shorthand border takes precedence over individual sides', function () {
    $this->subject->border = true;
    $this->subject->borderTop = true;
    $styles = $this->subject->borders();

    expect($styles)->toHaveKey('border');
    expect($styles)->not->toHaveKey('border-top');
});

it('includes border-radius when set', function () {
    $this->subject->borderRadius = '50%';
    expect($this->subject->borders()['border-radius'])->toBe('50%');
});

it('omits border-radius when not set', function () {
    expect($this->subject->borders())->not->toHaveKey('border-radius');
});
