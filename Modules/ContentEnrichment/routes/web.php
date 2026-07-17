<?php

use Illuminate\Support\Facades\Route;
use Modules\ContentEnrichment\Http\Controllers\ContentEnrichmentController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('contentenrichments', ContentEnrichmentController::class)->names('contentenrichment');
});
