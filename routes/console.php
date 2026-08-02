<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Schedule::command('scrape:archives')
    ->dailyAt('23:00')
    ->withoutOverlapping()
    ->onOneServer();
