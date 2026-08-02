<?php

use Illuminate\Support\Carbon;
use Modules\Crawling\Jobs\ScrapeArchivePageJob;

it('stores articles published after the since date', function (): void {
    config(['queue.default' => 'sync']);

    $since = Carbon::parse('2026-6-15');

    ScrapeArchivePageJob::dispatchSync('simonsinek.com', 1, $since);

    $this->assertDatabaseHas('scraped_contents', [
        'domain' => 'simonsinek.com',
    ]);
});
