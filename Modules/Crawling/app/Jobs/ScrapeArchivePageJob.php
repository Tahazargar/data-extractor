<?php

namespace Modules\Crawling\Jobs;

use Carbon\CarbonInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Crawling\Exceptions\FailedScrapeUrlException;
use Modules\Crawling\Exceptions\ScraperConfigNotFoundException;

class ScrapeArchivePageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly string $site,
        private readonly int $page,
        private readonly ?Carbon $since = null,
    ) {}

    /**
     * Execute the job.
     * @throws ScraperConfigNotFoundException
     */
    public function handle(): void
    {
        $sites = config("scrapers.sites");
        $config = $sites[$this->site] ?? null;

        if(!$config){
            throw new ScraperConfigNotFoundException($this->site);
        }

        $scraperClass = $config['concreteService'];
        $url = str_replace('{page}', $this->page, $config['blogArchiveUrl']);

        $response = Http::get($url);

        if($response->status() == 404){
            Log::info("Reached end of archive for site: {$this->site} at page {$this->page} (404). Pagination complete.");
            return;
        }

        if (!$response->ok()) {
            throw new FailedScrapeUrlException("Crawl failed with response: {$response->body()} at URL: {$url}");
        }

        $articleUrls = $scraperClass::extractArticleUrls($response->body());

        if(empty($articleUrls)){
            Log::info("No articles found for site: {$this->site}");
            return;
        }

        $cacheKey = "last_article_date:{$this->site}";
        $threshold = $this->since ?? $this->cachedThreshold($cacheKey);

        $newArticleUrls = [];
        $shouldStopPagination = false;

        foreach ($articleUrls as $articleUrl){
            if($threshold && $articleUrl->publishedAt !== null && $articleUrl->publishedAt->lte($threshold)){
                $shouldStopPagination = true;
                break;
            }

            $newArticleUrls[] = $articleUrl;
        }

        foreach ($newArticleUrls as $index => $articleUrl) {
            $delay = rand(15, 30) * ($index + 1);
            ScrapeArticleJob::dispatch($this->site, $articleUrl->url)->delay(now()->addSeconds($delay));
        }

        if (!empty($newArticleUrls) && $newArticleUrls[0]->publishedAt !== null) {
            Cache::forever($cacheKey, $newArticleUrls[0]->publishedAt->toDateTimeString());
        }

        if (!$shouldStopPagination) {
            $nextPageDelay = rand(30, 60);
            self::dispatch($this->site, $this->page + 1, $this->since)
                ->delay(now()->addSeconds($nextPageDelay));
        }
    }

    private function cachedThreshold(string $cacheKey): ?CarbonInterface
    {
        $cached = Cache::get($cacheKey);
        return $cached ? Carbon::parse($cached) : null;
    }
}
