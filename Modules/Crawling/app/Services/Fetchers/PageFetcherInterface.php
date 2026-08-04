<?php

namespace Modules\Crawling\Services\Fetchers;

interface PageFetcherInterface
{
    public function fetch(string $url): string;
}
