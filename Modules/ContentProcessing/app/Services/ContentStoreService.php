<?php

namespace Modules\ContentProcessing\Services;

use Illuminate\Support\Facades\Log;
use Modules\ContentProcessing\Models\ScrapedContent;
use Modules\Crawling\DTOs\ScraperResultDTO;
use Modules\Crawling\Exceptions\InsertScrapedContentException;
use Modules\Crawling\Services\Rules\ContentDeduplicator;

class ContentStoreService
{
    public function __construct(
        private readonly ContentDeduplicator $contentDeduplicator
    ){}

    /**
     * @throws InsertScrapedContentException
     */
    public function store(ScraperResultDTO $scraperResult): void
    {
        try {
            $exists = $this->contentDeduplicator->isDuplicate($scraperResult->contentHash);

            if ($exists){
                Log::info('Duplicated content skipped', [
                    'url' => $scraperResult->url,
                    'content_hash' => $scraperResult->contentHash
                ]);

                return;
            }

            ScrapedContent::create($scraperResult->toArray());
        } catch (\Throwable $e) {
            throw new InsertScrapedContentException($scraperResult->url, $e);
        }

    }
}
