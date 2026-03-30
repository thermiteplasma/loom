<?php

namespace Thermiteplasma\Loom;

use Illuminate\Support\ServiceProvider;
use Thermiteplasma\Loom\Services\ReportService;
use Thermiteplasma\Loom\Livewire\TemplateDesigner;

class LoomServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/loom.php', 'loom');

        $this->app->singleton(ReportService::class, function ($app) {
            return new ReportService(
                binary: config('loom.binary', 'weasyprint'),
                timeout: config('loom.timeout', 120),
                options: config('loom.options', []),
            );
        });

        $this->app->alias(ReportService::class, 'loom');
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'loom');

        $this->loadViewComponentsAs('loom', [
            // Main
            Components\Report::class,
            Components\Band::class,

            // Bands
            Components\Bands\Title::class,
            Components\Bands\PageHeader::class,
            Components\Bands\PageFooter::class,
            Components\Bands\ColumnHeader::class,
            Components\Bands\ColumnFooter::class,
            Components\Bands\GroupHeader::class,
            Components\Bands\GroupFooter::class,
            Components\Bands\Detail::class,
            Components\Bands\Summary::class,
            Components\Bands\LastPageFooter::class,
            Components\Bands\NoData::class,
            Components\Bands\Background::class,

            // Elements
            Components\Field::class,
            Components\StaticText::class,
            Components\Image::class,
            Components\Line::class,
            Components\Rectangle::class,
            Components\Ellipse::class,
            Components\Frame::class,
            Components\PageBreak::class,
            Components\Row::class,
            Components\Column::class,
            Components\Table::class,
            Components\TableColumn::class,
            Components\Subreport::class,
            Components\DataList::class,
            Components\PageNumber::class,
            Components\TotalPages::class,
            Components\CurrentDate::class,
            Components\Style::class,
        ]);

        // Designer UI — only registered when Livewire is installed
        if (class_exists(\Livewire\Component::class)) {
            \Livewire\Livewire::component('loom-template-designer', TemplateDesigner::class);
            $this->loadRoutesFrom(__DIR__.'/../routes/designer.php');
        }

        // Register designer Blade components under the loom:: namespace
        $this->loadViewComponentsAs('loom-designer', [
            Components\Designer\PropSection::class,
            Components\Designer\Text::class,
            Components\Designer\Textarea::class,
            Components\Designer\Select::class,
            Components\Designer\Checkbox::class,
            Components\Designer\Color::class,
        ]);

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/loom.php' => config_path('loom.php'),
            ], 'loom-config');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/loom'),
            ], 'loom-views');
        }
    }
}
