<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class CocService
{
    protected string $baseUrl;
    protected string $token;

    public function __construct()
    {
        $this->baseUrl = 'https://api.clashofclans.com/v1';
        $this->token = config('services.coc.token') ?: env('COC_API_TOKEN');
    }

    /**
     * Get player information by tag.
     */
    public function getPlayer(string $playerTag): array
    {
        return $this->makeRequest("/players/" . $this->encodeTag($playerTag));
    }

    /**
     * Get clan information by tag.
     */
    public function getClan(string $clanTag): array
    {
        return $this->makeRequest("/clans/" . $this->encodeTag($clanTag));
    }

    /**
     * Get clan members by tag.
     */
    public function getClanMembers(string $clanTag): array
    {
        return $this->makeRequest("/clans/" . $this->encodeTag($clanTag) . "/members");
    }

    /**
     * Get current war information for a clan.
     */
    public function getCurrentWar(string $clanTag): array
    {
        return $this->makeRequest("/clans/" . $this->encodeTag($clanTag) . "/currentwar");
    }

    /**
     * Get clan war league information for a clan.
     */
    public function getClanWarLeague(string $clanTag): array
    {
        return $this->makeRequest("/clans/" . $this->encodeTag($clanTag) . "/warleague/clans");
    }

    /**
     * Encode the tag (# to %23).
     */
    protected function encodeTag(string $tag): string
    {
        if (str_starts_with($tag, '#')) {
            return str_replace('#', '%23', $tag);
        }

        return '%23' . ltrim($tag, '#');
    }

    /**
     * Make a request to the CoC API.
     */
    protected function makeRequest(string $endpoint): array
    {
        $response = Http::withToken($this->token)
            ->acceptJson()
            ->get($this->baseUrl . $endpoint);

        if ($response->successful()) {
            return [
                'success' => true,
                'data' => $response->json(),
            ];
        }

        return $this->handleError($response);
    }

    /**
     * Handle error responses.
     */
    protected function handleError(Response $response): array
    {
        $status = $response->status();
        $message = match ($status) {
            403 => 'Forbidden: Check your API Token and IP Whitelist.',
            404 => 'Not Found: Invalid tag or data not available.',
            429 => 'Too Many Requests: Rate limit exceeded. Please try again later.',
            500 => 'Internal Server Error: Clash of Clans API is currently down.',
            default => 'An unexpected error occurred: ' . ($response->json('message') ?? 'Unknown error'),
        };

        return [
            'success' => false,
            'status' => $status,
            'message' => $message,
            'error' => $response->json(),
        ];
    }

    /**
     * Check if the API is configured correctly.
     */
    public function checkConfigStatus(): array
    {
        $hasToken = !empty($this->token);

        return [
            'success' => true,
            'api_configured' => $hasToken,
            'base_url' => $this->baseUrl,
            'token_exists' => $hasToken,
            'message' => $hasToken ? 'Configuration is valid.' : 'API Token is missing in .env (COC_API_TOKEN).',
        ];
    }
}
