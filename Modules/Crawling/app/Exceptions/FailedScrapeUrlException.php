<?php

namespace Modules\Crawling\Exceptions;

use Exception;

class FailedScrapeUrlException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
