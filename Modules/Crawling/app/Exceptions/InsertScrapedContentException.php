<?php

namespace Modules\Crawling\Exceptions;

use Exception;
use Throwable;

class InsertScrapedContentException extends Exception
{
    /**
     * @param string $url The URL whose scraped content failed to be inserted.
     * @param Throwable|null $previous The original exception (e.g. DB or Eloquent exception).
     */
    public function __construct(
        private readonly string $url,
        ?Throwable $previous = null
    ){
        parent::__construct("Scraped Content Failed: {$url}", 0, $previous);
    }
}
