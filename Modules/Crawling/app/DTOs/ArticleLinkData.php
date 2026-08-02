<?php

namespace Modules\Crawling\DTOs;

use Carbon\CarbonInterface;

final class ArticleLinkData
{
    public function __construct(
        public readonly string $url,
        public readonly ?CarbonInterface $publishedAt = null,
    ){}
}
