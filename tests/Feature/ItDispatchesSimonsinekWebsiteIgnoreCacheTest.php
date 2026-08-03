<?php

use Modules\Crawling\Jobs\ScrapeArchivePageJob;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Cache;

it('scrapes multiple pages for simonsinek.com when cache is bypassed', function () {
    Queue::fake();
    Cache::forget('last_article_date:simonsinek.com');

    $this->artisan('scrape:archives simonsinek.com')
        ->assertExitCode(0);

    Queue::assertPushed(ScrapeArchivePageJob::class, function ($job) {
        return $job->site === 'simonsinek.com'
            && $job->page === 1
            && $job->since === null;
    });
});
