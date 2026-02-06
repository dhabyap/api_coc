<?php

namespace App\Http\Controllers;

use App\Services\CocService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CocController extends Controller
{
    protected CocService $cocService;

    public function __construct(CocService $cocService)
    {
        $this->cocService = $cocService;
    }

    /**
     * Get player data.
     */
    public function player(string $playerTag): JsonResponse
    {
        $result = $this->cocService->getPlayer($playerTag);
        return $this->response($result);
    }

    /**
     * Get clan data.
     */
    public function clan(string $clanTag): JsonResponse
    {
        $result = $this->cocService->getClan($clanTag);
        return $this->response($result);
    }

    /**
     * Get clan members.
     */
    public function clanMembers(string $clanTag): JsonResponse
    {
        $result = $this->cocService->getClanMembers($clanTag);
        return $this->response($result);
    }

    /**
     * Get current war data.
     */
    public function war(string $clanTag): JsonResponse
    {
        $result = $this->cocService->getCurrentWar($clanTag);
        return $this->response($result);
    }

    /**
     * Get CWL data.
     */
    public function cwl(string $clanTag): JsonResponse
    {
        $result = $this->cocService->getClanWarLeague($clanTag);
        return $this->response($result);
    }

    /**
     * Check API status.
     */
    public function status(): JsonResponse
    {
        $result = $this->cocService->checkConfigStatus();
        return response()->json($result);
    }

    /**
     * Helper to format consistent responses.
     */
    protected function response(array $result): JsonResponse
    {
        $status = $result['success'] ? 200 : ($result['status'] ?? 500);
        return response()->json($result, $status);
    }
}
