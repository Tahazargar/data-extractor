<?php

namespace Modules\Crawling\Services\Fetchers;

use Illuminate\Support\Facades\Http;
use Modules\Crawling\Exceptions\ScrapeArticleJobFailedException;

class SimpleHttpFetcher implements PageFetcherInterface
{

    public function fetch(string $url): string
    {
        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ])->get($url);

        if(!$response->ok()){
            throw new ScrapeArticleJobFailedException($url);
        }

        return $response->body();
    }
}
