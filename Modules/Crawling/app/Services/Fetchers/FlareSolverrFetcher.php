<?php

namespace Modules\Crawling\Services\Fetchers;

use Illuminate\Support\Facades\Http;
use Modules\Crawling\Exceptions\ScrapeArticleJobFailedException;

class FlareSolverrFetcher implements PageFetcherInterface
{

    public function fetch(string $url): string
    {
        $response = Http::timeout(120) //
        ->post(config('crawling.flaresolverr_url'), [
            'cmd' => 'request.get',
            'url' => $url,
            'maxTimeout' => 120 * 1000, // Convert to milliseconds
        ]);

        if (!$response->ok()) {
            throw new ScrapeArticleJobFailedException($url);
        }

        $data = $response->json();

        if (
            ($data['status'] ?? '') !== 'ok' ||
            empty($data['solution']['response'] ?? '')
        ) {
            throw new ScrapeArticleJobFailedException($url);
        }

        return $data['solution']['response'];
    }


}
