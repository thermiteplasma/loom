<?php

use Illuminate\Support\Facades\Route;
use Thermiteplasma\Loom\Livewire\TemplateDesigner;

Route::middleware(['web'])->group(function () {
    Route::get('/loom/designer', TemplateDesigner::class)->name('loom.designer');
});
