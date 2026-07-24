<?php

return [
    'sites' => [
        'simonsinek.com' => [
            'concreteService' => \Modules\Crawling\Services\Scrapers\SimonSinekArticleScraper::class,
            'blogArchiveUrl' => 'https://simonsinek.com/stories/page/{page}'
        ],
    ]
];
