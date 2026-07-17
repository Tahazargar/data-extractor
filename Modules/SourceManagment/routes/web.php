<?php

use Illuminate\Support\Facades\Route;
use Modules\SourceManagment\Http\Controllers\SourceManagmentController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('sourcemanagments', SourceManagmentController::class)->names('sourcemanagment');
});
