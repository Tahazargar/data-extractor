<?php

declare(strict_types=1);

namespace Modules\Crawling\Services\Gmail;


use Modules\Crawling\Services\Gmail\DTOs\GmailMessageHtmlDTO;

/**
 * Responsible solely for querying Gmail and returning message HTML bodies.
 * Keeps search/listing logic out of the Command layer.
 */
class GmailMessageFetcherService
{
    public function __construct(
        private readonly GmailService $gmailService,
    ) {}

    /**
     * Fetch messages matching the given query and return their HTML bodies.
     *
     * @return array<int, GmailMessageHtmlDTO>
     */
    public function fetchHtmlMessages(string $query, int $limit): array
    {
        $messages = $this->gmailService->fetchMessages($query, $limit);

        $result = [];

        foreach ($messages as $message) {
            $html = $this->gmailService->getMessageHtml($message);

            $result[] = new GmailMessageHtmlDTO(
                id: $message->getId(),
                html: $html,
            );
        }

        return $result;
    }
}
