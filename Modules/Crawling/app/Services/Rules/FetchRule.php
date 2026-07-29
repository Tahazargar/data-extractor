<?php

namespace Modules\Crawling\Services\Rules;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Modules\ContentProcessing\Models\ScrapedContent;

final class FetchRule
{
    public int $refreshAfterHours = 24;

    public function shouldFetch(string $url): bool
    {
        $record = ScrapedContent::where('url', $url)
            ->select(['last_scraped_at'])
            ->first();

        if($record === null) {
            return true;
        }

        return Carbon::parse($record->last_scraped_at)
            ->addHours($this->refreshAfterHours)
            ->isPast();
    }
}
