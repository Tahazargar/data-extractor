<?php

namespace Modules\Crawling\Contracts;

use Modules\Crawling\DTOs\ScraperResult;

interface ArticleScraperInterface
{
    /**
     * @param string $html
     * @return array
     */
    public static function parseDetails(string $html, string $url): ScraperResult;

    public static function extractArticleUrls(string $html): array;
}
