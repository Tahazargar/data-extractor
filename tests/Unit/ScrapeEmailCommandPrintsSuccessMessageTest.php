<?php

use Modules\Crawling\Jobs\ScrapeArticleJob;
use Illuminate\Support\Facades\Queue;

it('dispatches job and prints success message', function (): void {
    Queue::fake();

    $this->artisan('scrape:emails', [
        'site' => 'marketingweek.com',
        'url'  => 'https://www.marketingweek.com/brand-promise-secondary-sell',
    ])
        ->expectsOutput('Job dispatched successfully.')
        ->assertExitCode(0);

    Queue::assertPushed(ScrapeArticleJob::class, fn (ScrapeArticleJob $job): bool =>
        $job->site === 'marketingweek.com'
        && $job->url === 'https://www.marketingweek.com/brand-promise-secondary-sell'
    );
});
