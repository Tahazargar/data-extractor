<?php

return [
    'sites' => [
        'simonsinek.com' => [
            'concreteService' => \Modules\Crawling\Services\Scrapers\SimonSinekUrlExtractor::class,
            'blogArchiveUrl' => 'https://simonsinek.com/stories/page/{page}'
        ],
        'marketingweek.com' => [
            'concreteService' => \Modules\Crawling\Services\Scrapers\MarketingWeekUrlExtractor::class,
            'blogArchiveUrl' => null,
            'bypassBot' => true,
        ],
    ]
];
