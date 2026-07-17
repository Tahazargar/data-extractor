<?php

namespace Modules\Crawling\Services\Scrapers;

use Carbon\Carbon;
use Illuminate\Http\Client\ConnectionException;
use Modules\Crawling\Contracts\ScraperInterface;
use Modules\Crawling\DTOs\ScraperResult;
use Modules\Crawling\Helpers\Helpers;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Http;

class SimonSinekScraper implements ScraperInterface
{
    /**
     * @throws ConnectionException
     */
    public static function scrapeArticle(string $url): ScraperResult
    {
        $response = Http::get($url);

        return SimonSinekScraper::parseDetails($response->body());
    }

    public static function parseDetails(string $html): ScraperResult
    {
        $crawler = new Crawler($html);

        // Example: Extract title
        $title = $crawler->filter('h1')->text();

        // Example: Extract content while preserving HTML structure for bold/subtitles
        $content = $crawler->filter('.prose')->html();

        $author = Helpers::extractMetaByIcon($crawler, 'pen-line');
        $readTime =   Helpers::extractMetaByIcon($crawler, 'clock');
        $datePublished = Helpers::extractMetaByIcon($crawler, 'calendar');

        // Transform to int
        $readTime = (int) filter_var($readTime, FILTER_SANITIZE_NUMBER_INT);

        // Transform to CarbonInterface
        $datePublished = Carbon::parse($datePublished);

        return ScraperResult::successfulScrapeArticle(
            title: $title,
            content: $content,
            author: $author,
            readTime: $readTime,
            date: $datePublished,
        );
    }
}
