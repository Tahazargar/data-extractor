<?php

use Illuminate\Support\Facades\Queue;
use Modules\Crawling\Jobs\ScrapeArchivePageJob;

it('dispatches jobs for all configured sites without since option', function () {
    Queue::fake();

    $sites = config('scrapers.sites');

    $this->artisan('scrape:archives')
        ->assertExitCode(0);

    foreach ($sites as $site => $config) {
        Queue::assertPushed(ScrapeArchivePageJob::class, function ($job) use ($site) {
            return $job->site === $site
                && $job->page === 1
                && $job->since === null;
        });
    }

    Queue::assertCount(count($sites));
});
