<?php

namespace Modules\Crawling\Exceptions;

use Exception;

class ScraperConfigNotFoundException extends Exception
{
    public function __construct(string $site)
    {
        return parent::__construct("No scraper config found for site: {$site}");
    }
}
