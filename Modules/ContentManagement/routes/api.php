<?php

use Illuminate\Support\Facades\Route;
use Modules\ContentManagement\Http\Controllers\ContentManagementController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('contentmanagements', ContentManagementController::class)->names('contentmanagement');
});
