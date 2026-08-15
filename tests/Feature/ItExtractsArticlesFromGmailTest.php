<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Queue;
use Modules\Crawling\Services\Gmail\DTOs\GmailMessageHtmlDTO;
use Modules\Crawling\Jobs\ScrapeArticleJob;
use Modules\Crawling\Services\Gmail\GmailClientService;
use Modules\Crawling\Services\Gmail\GmailMarketingWeeksUrlExtractor;
use Modules\Crawling\Services\Gmail\GmailMessageFetcherService;
use Symfony\Component\DomCrawler\Crawler;

beforeEach(function (): void {
    Queue::fake();
});

it('extracts urls directly from raw html', function () {
    $html = <<<HTML
<div>
  <div class="layout-grid-cell-content-mso">
    <div>
      <table style="background-color:#e71e4a">
        <tbody><tr><td>
          <table><tbody><tr><td>
            <div><p><strong>Editor's Picks</strong></p></div>
          </td></tr></tbody></table>
        </td></tr></tbody>
      </table>
    </div>
  </div>
  <div class="layout-grid-cell-content-mso">
    <div>
      <table>
        <tbody><tr><td>
          <pre><a href="https://marketingweek.com/article-1"><u>READ MORE</u></a></pre>
        </td></tr></tbody>
      </table>
    </div>
  </div>
  <div class="layout-grid-cell-content-mso">
    <div>
      <table>
        <tbody><tr><td>
          <pre><a href="https://marketingweek.com/article-2"><u>READ MORE</u></a></pre>
        </td></tr></tbody>
      </table>
    </div>
  </div>
</div>
HTML;


    $extractor = new GmailMarketingWeeksUrlExtractor();
    $urls = $extractor->extractUrls($html);
// Source - https://stackoverflow.com/a/32314916
// Posted by DiegoYungh
// Retrieved 2026-08-14, License - CC BY-SA 3.0


    dump($urls); // ببین چی چاپ میشه
    expect($urls)->toHaveCount(2);
});


it('dispatches scrape jobs for each extracted URL', function (): void {
    $html = <<<HTML
<table>
  <tbody><tr><td>
    <table style="background-color:#e71e4a">
      <tbody><tr><td>
        <table><tbody><tr><td>
          <div><p style="text-align:center">
            <span><strong>Editor's Picks</strong></span>
          </p></div>
        </td></tr></tbody></table>
      </td></tr></tbody></table>
  </td></tr></tbody>
</table>
<table>
  <tbody><tr><td>
    <pre><a href="https://marketingweek.com/article-1"><u>READ MORE</u></a></pre>
  </td></tr></tbody>
</table>
<table>
  <tbody><tr><td>
    <pre><a href="https://marketingweek.com/article-2"><u>READ MORE</u></a></pre>
  </td></tr></tbody>
</table>
HTML;

    // Bind GmailClientService mock FIRST to prevent real OAuth resolution
    $this->app->forgetInstance(GmailClientService::class);
    $this->app->bind(GmailClientService::class, fn() => Mockery::mock(GmailClientService::class));

    $fetcher = Mockery::mock(GmailMessageFetcherService::class);
    $fetcher->shouldReceive('fetchHtmlMessages')
        ->once()
        ->with('', 10)
        ->andReturn([
            new GmailMessageHtmlDTO(id: 'msg-1', html: $html),
        ]);

    $this->app->instance(GmailMessageFetcherService::class, $fetcher);

    $this->artisan('scrape:emails')
        ->expectsOutputToContain('Total URLs found: 2')
        ->assertExitCode(0);

    Queue::assertPushed(ScrapeArticleJob::class, 2);
    Queue::assertPushed(ScrapeArticleJob::class, fn($job) => $job->url === 'https://marketingweek.com/article-1');
    Queue::assertPushed(ScrapeArticleJob::class, fn($job) => $job->url === 'https://marketingweek.com/article-2');
});

it('returns success and warns when no emails found', function (): void {
    $fetcher = Mockery::mock(GmailMessageFetcherService::class);
    $fetcher->shouldReceive('fetchHtmlMessages')
        ->once()
        ->andReturn([]);

    $this->app->instance(GmailMessageFetcherService::class, $fetcher);

    $this->artisan('scrape:emails')
        ->expectsOutputToContain('No emails found.')
        ->assertExitCode(0);

    Queue::assertNothingPushed();
});

it('passes query and limit options to fetcher', function (): void {
    $fetcher = Mockery::mock(GmailMessageFetcherService::class);
    $fetcher->shouldReceive('fetchHtmlMessages')
        ->once()
        ->with('from:newsletter@marketingweek.com', 5)
        ->andReturn([]);

    $this->app->instance(GmailMessageFetcherService::class, $fetcher);

    $this->artisan('scrape:emails', [
        '--query' => 'from:newsletter@marketingweek.com',
        '--limit' => 5,
    ])->assertExitCode(0);
});

it('dispatches no jobs when html has no editors picks section', function (): void {
    $fetcher = Mockery::mock(GmailMessageFetcherService::class);
    $fetcher->shouldReceive('fetchHtmlMessages')
        ->once()
        ->andReturn([
            new GmailMessageHtmlDTO(id: 'msg-1', html: '<div>No relevant content</div>'),
        ]);

    $this->app->instance(GmailMessageFetcherService::class, $fetcher);

    $this->artisan('scrape:emails')
        ->expectsOutputToContain('Total URLs found: 0')
        ->assertExitCode(0);

    Queue::assertNothingPushed();
});
