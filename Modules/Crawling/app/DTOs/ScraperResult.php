<?php

namespace Modules\Crawling\DTOs;

use Carbon\CarbonInterface;

readonly class ScraperResult
{
    public function __construct(
        public string           $title,
        public string           $content,
        public ?bool            $status,
        public ?string          $author = null,
        public ?int             $readTime = null,
        public ?string          $categories = null,
        public ?string          $tags = null,
        public ?CarbonInterface $date = null,
        public ?array           $errors = null
    )
    {
    }

    public static function successfulScrapeArticle(string $title, string $content, ?string $author = null, ?int $readTime = null, ?string $categories = null, ?string $tags = null, ?bool $status = null, ?CarbonInterface $date = null): ScraperResult
    {
        return new self(
            title: $title,
            content: $content,
            status: $status,
            author: $author,
            readTime: $readTime,
            categories: $categories,
            tags: $tags,
            date: $date,
        );
    }

    public static function failedScrapeArticle(string $title, string $content, ?string $author = null, ?int $readTime = null, ?string $categories = null, ?string $tags = null, ?CarbonInterface $date = null, ?bool $status = null, ?array $errors = null): ScraperResult
    {
        return new self(
            title: $title,
            content: $content,
            status: $status,
            author: $author,
            readTime: $readTime,
            categories: $categories,
            tags: $tags,
            date: $date,
            errors: $errors,
        );
    }
}
