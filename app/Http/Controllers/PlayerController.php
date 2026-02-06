<?php

namespace App\Http\Controllers;

use App\Services\CocPlayerService;
use App\Services\PlayerInsightService;
use App\Services\GlobalStatsService;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    protected CocPlayerService $playerService;
    protected PlayerInsightService $insightService;
    protected GlobalStatsService $globalStatsService;

    public function __construct(
        CocPlayerService $playerService,
        PlayerInsightService $insightService,
        GlobalStatsService $globalStatsService
    ) {
        $this->playerService = $playerService;
        $this->insightService = $insightService;
        $this->globalStatsService = $globalStatsService;
    }

    /**
     * Show the homepage with global stats.
     */
    public function home()
    {
        $globalStats = $this->globalStatsService->getGlobalStats();
        return view('home', ['globalStats' => $globalStats]);
    }

    /**
     * Show the player search form redirect or direct search.
     */
    public function search(Request $request)
    {
        $request->validate([
            'tag' => 'required|string|min:3',
        ]);

        $tag = str_replace('#', '', $request->input('tag'));
        return redirect()->route('player.show', ['tag' => $tag]);
    }

    /**
     * Search for a player and show results.
     */
    public function show(string $tag)
    {
        $result = $this->playerService->getPlayerByTag($tag);

        if (!$result['success']) {
            return redirect()->route('player.home')
                ->with('error', $result['message'] ?? 'Player not found.')
                ->withInput();
        }

        $player = $result['data'];

        // Get Advanced Insights
        $insights = $this->insightService->getAllInsights($player);
        $recommendations = $this->insightService->getRecommendations($player, $insights);

        return view('player-result', [
            'player' => $player,
            'insights' => $insights,
            'recommendations' => $recommendations,
            'lastFetchedAt' => $result['last_fetched_at'],
            'source' => $result['source'],
        ]);
    }
}
