<?php

declare(strict_types=1);

namespace Modules\Crawling\Services\Gmail;

use Google_Client;
use Google_Service_Gmail;
use RuntimeException;

class GmailClientService
{
    private Google_Client $client;

    public function __construct()
    {
        $this->client = $this->buildClient();
    }

    /**
     * Build and configure the Google Client instance.
     */
    private function buildClient(): Google_Client
    {
        $client = new Google_Client();

        $client->setClientId((string) config('services.gmail.client_id'));
        $client->setClientSecret((string) config('services.gmail.client_secret'));
        $client->setRedirectUri((string) config('services.gmail.redirect_uri'));
        $client->setAccessType('offline');
        $client->setPrompt('consent'); // Forces refresh_token on first consent
        $client->addScope(Google_Service_Gmail::GMAIL_READONLY);
        // Add more scopes as needed, e.g. GMAIL_SEND, GMAIL_MODIFY

        if ($this->hasStoredToken()) {
            $client->setAccessToken($this->loadStoredToken());
        }

        // Refresh token automatically if expired
        if ($client->isAccessTokenExpired()) {
            $this->refreshAccessToken($client);
        }

        return $client;
    }

    /**
     * Generate the OAuth consent screen URL.
     */
    public function getAuthUrl(): string
    {
        return $this->client->createAuthUrl();
    }

    /**
     * Exchange authorization code for an access token and persist it.
     *
     * @throws RuntimeException
     */
    public function fetchAccessTokenWithAuthCode(string $code): array
    {
        $token = $this->client->fetchAccessTokenWithAuthCode($code);

        if (isset($token['error'])) {
            throw new RuntimeException('Gmail OAuth error: ' . $token['error']);
        }

        $this->storeToken($token);

        return $token;
    }

    /**
     * Refresh an expired access token using the stored refresh_token.
     *
     * @throws RuntimeException
     */
    private function refreshAccessToken(Google_Client $client): void
    {
        $refreshToken = $client->getRefreshToken();

        if ($refreshToken === null) {
            throw new RuntimeException('No refresh token available. Re-authentication required.');
        }

        $newToken = $client->fetchAccessTokenWithRefreshToken($refreshToken);

        if (isset($newToken['error'])) {
            throw new RuntimeException('Failed to refresh Gmail token: ' . $newToken['error']);
        }

        $this->storeToken($newToken);
    }

    /**
     * Check if a token file already exists.
     */
    private function hasStoredToken(): bool
    {
        return file_exists($this->tokenPath());
    }

    /**
     * Load the stored token from disk as an associative array.
     */
    private function loadStoredToken(): array
    {
        $contents = file_get_contents($this->tokenPath());

        return json_decode($contents, true) ?? [];
    }

    /**
     * Persist the token array to disk as JSON.
     */
    private function storeToken(array $token): void
    {
        file_put_contents(
            $this->tokenPath(),
            json_encode($token, JSON_PRETTY_PRINT)
        );
    }

    /**
     * Resolve the absolute path to the token storage file.
     */
    private function tokenPath(): string
    {
        return base_path((string) config('services.gmail.token_path'));
    }

    /**
     * Expose the underlying Gmail service for API calls.
     */
    public function getService(): Google_Service_Gmail
    {
        return new Google_Service_Gmail($this->client);
    }
}
