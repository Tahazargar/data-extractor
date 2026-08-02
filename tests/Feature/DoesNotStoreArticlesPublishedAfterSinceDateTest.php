<?php

use Illuminate\Support\Carbon;
use Modules\Crawling\Jobs\ScrapeArchivePageJob;

it('does not store articles published before the since date', function (): void {
    config(['queue.default' => 'sync']);

    // A future date — all existing articles are older than this
    $since = Carbon::now()->addYears(10);

    ScrapeArchivePageJob::dispatchSync('simonsinek.com', 1, $since);

    $this->assertDatabaseMissing('scraped_contents', [
        'domain' => 'simonsinek.com',
    ]);
});
