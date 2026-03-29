<?php

use Thermiteplasma\Loom\Concerns\BuildsStyles;

beforeEach(function () {
    $this->subject = new class {
        use BuildsStyles;

        public function build(array $declarations): string
        {
            return $this->buildStyleString($declarations);
        }
    };
});

it('formats property-value pairs as CSS', function () {
    expect($this->subject->build(['color' => 'red', 'font-size' => '12pt']))
        ->toBe('color: red; font-size: 12pt');
});

it('filters out null values', function () {
    expect($this->subject->build(['color' => 'red', 'width' => null]))
        ->toBe('color: red');
});

it('filters out false values', function () {
    expect($this->subject->build(['color' => 'red', 'border' => false]))
        ->toBe('color: red');
});

it('filters out empty string values', function () {
    expect($this->subject->build(['color' => 'red', 'margin' => '']))
        ->toBe('color: red');
});

it('returns empty string when all values are filtered', function () {
    expect($this->subject->build(['color' => null, 'border' => false, 'margin' => '']))
        ->toBe('');
});

it('preserves the value zero as a string', function () {
    expect($this->subject->build(['opacity' => '0']))
        ->toBe('opacity: 0');
});
