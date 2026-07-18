<?php

namespace Modules\SourceManagment\Support;

use InvalidArgumentException;
use Illuminate\Support\Facades\Log;

class ScraperFactory
{
    public function __construct(private array $map = [])
    {}

    public function resolve(string $url)
    {
        $host = (string) preg_replace('/^www\./', '', parse_url($url, PHP_URL_HOST) ?? '');

        $scraperMap = $this->map ?: config('scrapers.map', []);

        foreach ($scraperMap as $domain => $scraperClass) {
            if($host === $domain || str_ends_with($host, ".{$domain}")) {
                return app($scraperClass);
            }
        }

        throw new InvalidArgumentException("No scraper registered for host: {$host}");
    }
}
