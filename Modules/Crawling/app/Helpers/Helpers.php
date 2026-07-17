<?php

namespace Modules\Crawling\Helpers;

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
}
