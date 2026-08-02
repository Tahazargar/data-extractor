<?php

use Modules\Crawling\Jobs\ScrapeArchivePageJob;

it('stores article data after full pipeline runs', function (): void {
    config(['queue.default' => 'sync']);

    ScrapeArchivePageJob::dispatchSync('simonsinek.com', 1);

    $this->assertDatabaseHas('scraped_contents', [
        'domain' => 'simonsinek.com',
    ]);
});
