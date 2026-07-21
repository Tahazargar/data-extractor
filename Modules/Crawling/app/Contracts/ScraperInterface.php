<?php

namespace Modules\Crawling\Contracts;

use Modules\Crawling\DTOs\ScraperResult;

interface ScraperInterface
{
    /**
     * @param string $url
     * @return array
     */
    public function scrapeArticle(string $url): ScraperResult;

    /**
     * @param string $html
     * @return array
     */
    public function parseDetails(string $html, string $url): ScraperResult;
}
