<?php

use Illuminate\Support\Facades\Route;
use Modules\Crawling\Http\Controllers\CrawlingController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('crawlings', CrawlingController::class)->names('crawling');
});
