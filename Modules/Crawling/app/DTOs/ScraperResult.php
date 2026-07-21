<?php

namespace Modules\Crawling\DTOs;

use Carbon\CarbonInterface;

readonly class ScraperResult
{
    public function __construct(
        public string           $title,
        public string           $content,
        public string           $domain,
        public string           $url,
        public string           $contentHash,
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

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
            'content_hash' => $this->contentHash,
            'domain' => $this->domain,
            'url' => $this->url,
            'status' => $this->status,
            'author' => $this->author,
            'readTime' => $this->readTime,
            'categories' => $this->categories,
            'tags' => $this->tags,
            'published_at' => $this->date,
            'errors' => $this->errors
        ];
    }

    public static function successfulScrapeArticle(string $title, string $content, string $domain, string $url, string $contentHash, ?string $author = null, ?int $readTime = null, ?string $categories = null, ?string $tags = null, ?bool $status = null, ?CarbonInterface $date = null): ScraperResult
    {
        return new self(
            title: $title,
            content: $content,
            domain: $domain,
            url: $url,
            contentHash: $contentHash,
            status: $status,
            author: $author,
            readTime: $readTime,
            categories: $categories,
            tags: $tags,
            date: $date,
        );
    }

    public static function failedScrapeArticle(string $title, string $content, string $domain, string $url, string $contentHash, ?string $author = null, ?int $readTime = null, ?string $categories = null, ?string $tags = null, ?CarbonInterface $date = null, ?bool $status = null, ?array $errors = null): ScraperResult
    {
        return new self(
            title: $title,
            content: $content,
            domain: $domain,
            url: $url,
            contentHash: $contentHash,
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
