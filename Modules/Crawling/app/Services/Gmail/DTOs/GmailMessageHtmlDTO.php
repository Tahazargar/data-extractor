<?php

declare(strict_types=1);

namespace Modules\Crawling\Services\Gmail\DTOs;

/**
 * Immutable data object representing a Gmail message's ID and raw HTML body.
 */
final readonly class GmailMessageHtmlDTO
{
    public function __construct(
        public string $id,
        public string $html,
    ) {}
}
