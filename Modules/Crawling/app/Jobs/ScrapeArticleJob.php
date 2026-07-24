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

final class ScrapeArticleJob implements ShouldQueue
{
    use Dispatchable, Queueable, InteractsWithQueue, SerializesModels;

    public int $tries = 3;
    public int $timeout = 300;

    public function __construct(
        private readonly string $site,
        private readonly string $url,
    ) {}

    /**
     * Execute the job.
     * @throws ScrapeArticleJobFailedException
     * @throws ConnectionException
     */
    public function handle(): void {
        $sites = config('scrapers.sites');
        $site = $sites[$this->site];

        $scraperClass = $site['concreteService'];

        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ])->get($this->url);

        if(!$response->ok()){
            $this->release(60);

            throw new ScrapeArticleJobFailedException($this->url);
        }

        $articleData = $scraperClass::parseDetails($response->body(), $this->url);

        CrawlingFinishEvent::dispatch($articleData);
    }

    public function failed(\Throwable $e): void
    {
        Log::error("URL crawling failed permanently. url: {$this->url}", [
            'exception' => $e->getMessage(),
        ]);
    }
}
