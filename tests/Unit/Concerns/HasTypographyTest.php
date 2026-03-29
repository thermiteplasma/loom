<?php

use Thermiteplasma\Loom\Concerns\{BuildsStyles, HasTypography};

beforeEach(function () {
    $this->subject = new class {
        use BuildsStyles, HasTypography;

        public function typography(): array
        {
            return $this->typographyStyles();
        }

        public function decoration(): ?string
        {
            return $this->textDecoration();
        }
    };
});

it('returns null text-decoration when no flags are set', function () {
    expect($this->subject->decoration())->toBeNull();
});

it('returns underline when underline is true', function () {
    $this->subject->underline = true;
    expect($this->subject->decoration())->toBe('underline');
});

it('returns line-through when strikethrough is true', function () {
    $this->subject->strikethrough = true;
    expect($this->subject->decoration())->toBe('line-through');
});

it('combines underline and line-through when both are set', function () {
    $this->subject->underline = true;
    $this->subject->strikethrough = true;
    expect($this->subject->decoration())->toBe('underline line-through');
});

it('outputs font-weight bold when bold is true', function () {
    $this->subject->bold = true;
    expect($this->subject->typography()['font-weight'])->toBe('bold');
});

it('outputs null font-weight when bold is false', function () {
    expect($this->subject->typography()['font-weight'])->toBeNull();
});

it('outputs font-style italic when italic is true', function () {
    $this->subject->italic = true;
    expect($this->subject->typography()['font-style'])->toBe('italic');
});

it('outputs null font-style when italic is false', function () {
    expect($this->subject->typography()['font-style'])->toBeNull();
});

it('passes through scalar typography properties', function () {
    $this->subject->fontFamily = 'Arial';
    $this->subject->fontSize = '10pt';
    $this->subject->color = '#000';
    $this->subject->align = 'center';
    $this->subject->lineHeight = '1.5';

    $styles = $this->subject->typography();

    expect($styles['font-family'])->toBe('Arial');
    expect($styles['font-size'])->toBe('10pt');
    expect($styles['color'])->toBe('#000');
    expect($styles['text-align'])->toBe('center');
    expect($styles['line-height'])->toBe('1.5');
});
