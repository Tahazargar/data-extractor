<?php

namespace Modules\Crawling\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Modules\Crawling\Services\CrawlingService;

final class CrawlUrlJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 300;

    public function __construct(
        public readonly string $url,
    ) {
        $this->onQueue('crawling');
    }

    /**
     * Execute the job.
     */
    public function handle(CrawlingService $crawlingService): void {
        $articleData = $crawlingService->process($this->url);

        Log::info('URL crawled successfully.', [
            'URL' => $this->url,
            'Article Data' => $articleData,
        ]);
    }

    public function backoff(): array
    {
        return [300, 600, 900];
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('URL crawling failed permanently.', [
            'url' => $this->url,
            'exception' => $exception->getMessage(),
        ]);
    }
}
