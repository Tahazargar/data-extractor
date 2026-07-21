<?php

namespace Modules\ContentProcessing\Services;

use Illuminate\Support\Facades\Log;
use Modules\ContentProcessing\Models\ScrapedContent;
use Modules\Crawling\DTOs\ScraperResult;

class ContentStoreService
{
    public static function store(ScraperResult $scraperResult): void
    {
        try {
            $exists = ScrapedContent::query()->where('content_hash', $scraperResult->contentHash)->exists();

            if ($exists){
                Log::info('Duplicated content skipped', [
                    'url' => $scraperResult->url,
                    'content_hash' => $scraperResult->contentHash
                ]);

                return;
            }

            ScrapedContent::create($scraperResult->toArray());
        } catch (\Exception $e) {
            Log::error("Insert Scraped Content Failed: " . $e->getMessage());
        }

    }
}
