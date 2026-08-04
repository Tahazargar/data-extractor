<?php

namespace Modules\Crawling\Services\Scrapers;

use Illuminate\Support\Carbon;
use Modules\Crawling\Contracts\UrlExtractorInterface;
use Modules\Crawling\DTOs\ArticleLinkData;
use Modules\Crawling\DTOs\ScraperResultDTO;
use Modules\Crawling\Enums\ScrapeStatusEnum;
use Modules\Crawling\Support\Helpers;
use Symfony\Component\DomCrawler\Crawler;

class SimonSinekUrlExtractor implements UrlExtractorInterface
{
    public function parseDetails(string $url, string $html): ScraperResultDTO
    {
        $crawler = new Crawler($html);

        $title = $crawler->filter('h1')->text();

        // Example: Extract content while preserving HTML structure for bold/subtitles
        $content = $crawler->filter('.prose')->html();
        $contentHash = Helpers::contentHashGenerator($content);

        $author = Helpers::extractMetaByIcon($crawler, 'pen-line');
        $readTime =   Helpers::extractMetaByIcon($crawler, 'clock');
        $datePublished = Helpers::extractMetaByIcon($crawler, 'calendar');

        // Transform to int
        $readTime = (int) filter_var($readTime, FILTER_SANITIZE_NUMBER_INT);

        // Transform to CarbonInterface
        $datePublished = Carbon::parse($datePublished);

        $domain = Helpers::extractDomain($url);

        return ScraperResultDTO::successfulScrapeArticle(
            title: $title,
            content: $content,
            domain: $domain,
            url: $url,
            contentHash: $contentHash,
            author: $author,
            readTime: $readTime,
            status: ScrapeStatusEnum::Successful,
            date: $datePublished,
        );
    }

    public function extractUrls(string $html): array
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new \DOMXPath($dom);

        $articles = [];

        // Adjust selector to match the actual <a> inside <article>
        $nodes = $xpath->query('//article//a[@href]');

        foreach ($nodes as $node) {
            $href = $node->getAttribute('href');

            if(str_starts_with($href, '/')) {
                $href = "https://simonsinek.com" . $href;
            }

            $publishedAtNode = $xpath->query('.//p/span[1]', $node)->item(0);

            $publishedAt = Carbon::parse($publishedAtNode?->textContent);

            $articles[$href] = new ArticleLinkData(
                url: $href,
                publishedAt: $publishedAt,
            );
        }

        return $articles;
    }
}
