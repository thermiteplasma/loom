<?php

use Illuminate\Support\Collection;
use Thermiteplasma\Loom\DataSources\CallbackDataSource;

it('invokes the callback and returns its result', function () {
    $source = new CallbackDataSource(fn () => collect([1, 2, 3]));

    expect($source->resolve())->toBeInstanceOf(Collection::class)->toHaveCount(3);
});

it('passes params to the callback', function () {
    $received = null;

    $source = new CallbackDataSource(function (array $params) use (&$received) {
        $received = $params;

        return collect();
    });

    $source->resolve(['key' => 'value']);

    expect($received)->toBe(['key' => 'value']);
});

it('resolves with empty params by default', function () {
    $source = new CallbackDataSource(fn (array $params) => collect($params));

    expect($source->resolve())->toBeEmpty();
});
