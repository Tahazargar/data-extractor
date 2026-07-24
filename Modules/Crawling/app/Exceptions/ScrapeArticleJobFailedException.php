<?php

namespace Modules\Crawling\Exceptions;

use Exception;

class ScrapeArticleJobFailedException extends Exception
{
    public function __construct(string $url)
    {
        parent::__construct("Scrape article job failed for {$url}");
    }
}

