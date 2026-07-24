<?php

namespace Modules\Crawling\Support;

use Symfony\Component\DomCrawler\Crawler;

class Helpers
{
    public static function extractMetaByIcon(Crawler $article, string $iconName): ?string
    {
        $node = $article->filterXPath(
            "//span[.//svg[@data-icon='lucide:{$iconName}']]"
        );

        return $node->count() > 0
            ? trim($node->first()->text())
            : null;
    }

    public static function extractDomain(string $url): ?string
    {
        $host = parse_url($url, PHP_URL_HOST);

        return $host !== null ? preg_replace('/^www\./', '', $host) : null;
    }

    public static function extractCleanText(string $html): string
    {
        $crawler = new Crawler($html);

        // Remove noisy/non-content nodes before extracting text
        $crawler->filter('script, style, iframe, .ads, .comments, .share-buttons')
            ->each(fn (Crawler $node) => $node->getNode(0)?->parentNode?->removeChild($node->getNode(0)));

        return trim($crawler->filter('body')->text(''));
    }

    public static function contentHashGenerator(string $content): string
    {
        $cleanText = self::extractCleanText($content);

        $normalizedContent = $normalizedContent = trim(preg_replace('/\s+/', ' ', strtolower($cleanText)) ?? '');

        return hash('sha256', $normalizedContent);
    }
}
