<?php

namespace Modules\Crawling\Services\Rules;

use Modules\ContentProcessing\Models\ScrapedContent;

final class ContentDeduplicator
{
    public function isDuplicate(string $contentHash): bool
    {
        return ScrapedContent::where('content_hash', $contentHash)->exists();
    }
}
