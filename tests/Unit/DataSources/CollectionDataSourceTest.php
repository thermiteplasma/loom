<?php

use Thermiteplasma\Loom\DataSources\CollectionDataSource;

it('returns the collection unchanged', function () {
    $items = collect([['name' => 'Alice'], ['name' => 'Bob']]);
    $source = new CollectionDataSource($items);

    expect($source->resolve())->toBe($items);
});

it('ignores params and always returns the full collection', function () {
    $items = collect([1, 2, 3]);
    $source = new CollectionDataSource($items);

    expect($source->resolve(['any' => 'param']))->toBe($items);
});
