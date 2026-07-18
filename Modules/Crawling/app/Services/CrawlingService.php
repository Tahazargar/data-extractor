<?php

namespace Modules\Crawling\Services;

use Illuminate\Support\Facades\Log;
use Modules\SourceManagment\Support\ScraperFactory;

class CrawlingService
{
    public function __construct(
        private readonly ScraperFactory $scraperFactory
    )
    {
    }

    public function process(string $url)
    {
        $scraper = $this->scraperFactory->resolve($url);

        return $scraper->scrapeArticle($url);
    }
}
