<?php

use Illuminate\Support\Facades\Route;
use Modules\Crawling\Http\Controllers\CrawlingController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('crawlings', CrawlingController::class)->names('crawling');
});
