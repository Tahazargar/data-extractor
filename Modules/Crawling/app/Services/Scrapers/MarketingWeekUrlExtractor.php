<?php

namespace Modules\Crawling\Services\Scrapers;

use Illuminate\Support\Carbon;
use Modules\Crawling\Contracts\UrlExtractorInterface;
use Modules\Crawling\DTOs\ScraperResultDTO;
use Modules\Crawling\Enums\ScrapeStatusEnum;
use Modules\Crawling\Support\Helpers;
use Symfony\Component\DomCrawler\Crawler;

class MarketingWeekUrlExtractor implements UrlExtractorInterface
{
    private string $domain = 'marketingweek.com';

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function parseDetails(string $url, string $html): ScraperResultDTO
    {
        $crawler = new Crawler($html);

        // Extract title
        $title = $crawler->filter('#content .page-title')->count() > 0
            ? trim($crawler->filter('#content .page-title')->text())
            : null;

        // Extract first author only
        $author = $crawler->filter('#content div.metadata a.author')->count() > 0
            ? trim($crawler->filter('#content div.metadata a.author')->first()->text())
            : null;

        // Extract date and parse with Carbon
        $datePublished = null;
        if ($crawler->filter('#content div.metadata span.hentry-date')->count() > 0) {
            $dateText = trim($crawler->filter('#content div.metadata span.hentry-date')->text());
            try {
                $datePublished = Carbon::parse($dateText);
            } catch (\Exception $e) {
                $datePublished = null;
            }
        }

        // Extract content from #content div
        $content = $crawler->filter('#content')->count() > 0
            ? $crawler->filter('#content')->html()
            : null;

        $contentHash = Helpers::contentHashGenerator($content);

        return ScraperResultDTO::successfulScrapeArticle(
            title: $title,
            content: $content,
            domain: $this->getDomain(),
            url: $url,
            contentHash: $contentHash,
            author: $author,
            readTime: null,
            status: ScrapeStatusEnum::Successful,
            date: $datePublished,
        );
    }

    public function extractUrls(string $html): array
    {
        return [];
    }
}
