<?php

declare(strict_types=1);

namespace Modules\Crawling\Services\Gmail;

use Google\Service\Gmail\Message;

final class GmailService
{
    public function __construct(
        private readonly GmailClientService $clientService,
    ) {}

    /**
     * @return array<int, Message>
     */
    public function fetchMessages(string $query, int $limit): array
    {
        $service = $this->clientService->getService();

        $response = $service->users_messages->listUsersMessages('me', [
            'q'          => $query,
            'maxResults' => $limit,
        ]);

        return $response->getMessages() ?? [];
    }

    public function getMessageHtml(Message $message): string
    {
        $service = $this->clientService->getService();

        $full = $service->users_messages->get('me', $message->getId(), [
            'format' => 'full',
        ]);

        return $this->extractHtml($full->getPayload());
    }

    private function extractHtml(\Google\Service\Gmail\MessagePart $payload): string
    {
        $mimeType = $payload->getMimeType();

        if ($mimeType === 'text/html') {
            return base64_decode(strtr($payload->getBody()->getData(), '-_', '+/'));
        }

        foreach ($payload->getParts() ?? [] as $part) {
            $html = $this->extractHtml($part);
            if ($html !== '') {
                return $html;
            }
        }

        return '';
    }
}
