<?php

use Illuminate\Support\Facades\Route;
use Modules\ContentProcessing\Http\Controllers\ContentProcessingController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('contentprocessings', ContentProcessingController::class)->names('contentprocessing');
});
