<?php

namespace Modules\Crawling\Enums;

enum ScrapeStatusEnum: string
{
    case Successful   = 'successful';
    case Failed    = 'failed';
}
