<?php

use Thermiteplasma\Loom\DataSources\QueryDataSource;
use Thermiteplasma\Loom\Contracts\ReportDataSource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    Schema::create('query_ds_items', function ($table) {
        $table->id();
        $table->string('category');
        $table->string('name');
    });

    DB::table('query_ds_items')->insert([
        ['category' => 'A', 'name' => 'Alpha'],
        ['category' => 'B', 'name' => 'Beta'],
        ['category' => 'A', 'name' => 'Gamma'],
    ]);
});

afterEach(function () {
    Schema::dropIfExists('query_ds_items');
});

$model = new class extends Model {
    protected $table = 'query_ds_items';
    public $timestamps = false;
};

it('implements ReportDataSource', function () use ($model) {
    $source = new QueryDataSource($model->newQuery());
    expect($source)->toBeInstanceOf(ReportDataSource::class);
});

it('resolves all rows when no params given', function () use ($model) {
    $source = new QueryDataSource($model->newQuery());
    expect($source->resolve())->toHaveCount(3);
});

it('applies params as where clauses', function () use ($model) {
    $source = new QueryDataSource($model->newQuery());
    expect($source->resolve(['category' => 'A']))->toHaveCount(2);
});

it('does not mutate the original query', function () use ($model) {
    $query = $model->newQuery();
    $source = new QueryDataSource($query);

    $source->resolve(['category' => 'A']);

    // Original query should still return all rows
    expect($source->resolve())->toHaveCount(3);
});
