<?php

use Illuminate\Support\Facades\Route;
use Modules\ContentEnrichment\Http\Controllers\ContentEnrichmentController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('contentenrichments', ContentEnrichmentController::class)->names('contentenrichment');
});
