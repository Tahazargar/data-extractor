<?php

namespace Modules\Crawling\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Crawling\Events\CrawlingFinishEvent;
use Modules\Crawling\Exceptions\ScrapeArticleJobFailedException;
use Modules\Crawling\Services\Fetchers\FlareSolverrFetcher;
use Modules\Crawling\Services\Fetchers\SimpleHttpFetcher;
use Modules\Crawling\Services\Rules\FetchRule;

final class ScrapeArticleJob implements ShouldQueue
{
    use Dispatchable, Queueable, InteractsWithQueue, SerializesModels;

    public int $tries = 3;
    public int $timeout = 300;

    public function __construct(
        public readonly string $site,
        public readonly string $url,
    ) {}

    /**
     * Execute the job.
     * @throws ScrapeArticleJobFailedException
     * @throws ConnectionException
     */
    public function handle(FetchRule $fetchRule): void {
        $fetchRule->shouldFetch($this->url);

        $sites = config('scrapers.sites');
        $site = $sites[$this->site];

        $scraperClass = $site['concreteService'];

        $fetcher = ($site['bypassBot'] ?? false)
        ? new FlareSolverrFetcher()
            : new SimpleHttpFetcher();

        try {
            $html = $fetcher->fetch($this->url);
        } catch (ScrapeArticleJobFailedException) {
            $this->release(60);
            throw new ScrapeArticleJobFailedException($this->url);
        }

        $articleData = (new $scraperClass())->parseDetails($this->url, $html);

        CrawlingFinishEvent::dispatch($articleData);
    }

    public function failed(\Throwable $e): void
    {
        Log::error("URL crawling failed permanently. url: {$this->url}", [
            'exception' => $e->getMessage(),
        ]);
    }
}
