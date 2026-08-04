<?php

use Illuminate\Support\Facades\Http;
use Modules\Crawling\Jobs\ScrapeArticleJob;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('stores domain and url in database', function (): void {
    Http::fake([
        'http://flaresolverr:8191/v1' => Http::response([
            'status' => 'ok',
            'solution' => [
                'response' => '<html><head><title>Test</title></head><body><div id="content"><h1 class="page-title">Test Title</h1><div class="metadata"><a class="author">John Doe</a><span class="hentry-date">3 Aug 2026</span></div></div></body></html>',
            ],
        ], 200),
    ]);

    ScrapeArticleJob::dispatchSync(
        site: 'marketingweek.com',
        url: 'https://www.marketingweek.com/brand-promise-secondary-sell'
    );

    $this->assertDatabaseHas('scraped_contents', [
        'domain' => 'marketingweek.com',
        'url'    => 'https://www.marketingweek.com/brand-promise-secondary-sell',
    ]);
});
