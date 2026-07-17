<?php

use Illuminate\Support\Facades\Route;
use Modules\ContentProcessing\Http\Controllers\ContentProcessingController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('contentprocessings', ContentProcessingController::class)->names('contentprocessing');
});
