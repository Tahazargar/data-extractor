<?php

namespace Modules\Crawling\Contracts;

use Modules\Crawling\DTOs\ScraperResultDTO;

interface UrlExtractorInterface
{
    /**
     * @param string $html
     * @return array
     */
    public function parseDetails(string $url, string $html): ScraperResultDTO;

    public function extractUrls(string $html): array;
}
