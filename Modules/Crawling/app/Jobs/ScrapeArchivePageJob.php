<?php

namespace Modules\Crawling\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Crawling\Exceptions\FailedScrapeUrlException;
use Modules\Crawling\Exceptions\ScraperConfigNotFoundException;
use Modules\Crawling\Services\Scrapers\SimonSinekArticleScraper;

class ScrapeArchivePageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly string $site,
        private readonly int $page
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

        foreach ($articleUrls as $index => $articleUrl) {
            $delay = rand(15, 30) * ($index + 1);
            ScrapeArticleJob::dispatch($this->site, $articleUrl)->delay(now()->addSeconds($delay));
        }

        $nextPageDelay = rand(30, 60);
        self::dispatch($this->site, $this->page + 1)->delay(now()->addSeconds($nextPageDelay));
    }
}
