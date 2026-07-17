<?php

namespace Modules\Crawling\Contracts;

use Modules\Crawling\DTOs\ScraperResult;

interface ScraperInterface
{
    /**
     * @param string $url
     * @return array
     */
    public static function scrapeArticle(string $url): ScraperResult;

    /**
     * @param string $html
     * @return array
     */
    public static function parseDetails(string $html): ScraperResult;
}
