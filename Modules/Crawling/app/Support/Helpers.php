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

    public static function contentHashGenerator(string $content): string
    {
        return hash('sha256', $content);
    }

}
