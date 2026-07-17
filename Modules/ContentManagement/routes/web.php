<?php

use Illuminate\Support\Facades\Route;
use Modules\ContentManagement\Http\Controllers\ContentManagementController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('contentmanagements', ContentManagementController::class)->names('contentmanagement');
});
