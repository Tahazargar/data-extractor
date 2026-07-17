<?php

use Illuminate\Support\Facades\Route;
use Modules\SourceManagment\Http\Controllers\SourceManagmentController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('sourcemanagments', SourceManagmentController::class)->names('sourcemanagment');
});
