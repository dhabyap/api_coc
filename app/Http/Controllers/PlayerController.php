<?php

namespace App\Http\Controllers;

use App\Services\CocPlayerService;
use App\Services\PlayerInsightService;
use App\Services\GlobalStatsService;
use App\Services\CocEventService;
use Illuminate\Http\Request;

use App\Models\Suggestion;

class PlayerController extends Controller
{
    protected CocPlayerService $playerService;
    protected PlayerInsightService $insightService;
    protected GlobalStatsService $globalStatsService;
    protected CocEventService $eventService;

    public function __construct(
        CocPlayerService $playerService,
        PlayerInsightService $insightService,
        GlobalStatsService $globalStatsService,
        CocEventService $eventService
    ) {
        $this->playerService = $playerService;
        $this->insightService = $insightService;
        $this->globalStatsService = $globalStatsService;
        $this->eventService = $eventService;
    }

    /**
     * Show the homepage with global stats.
     */
    public function home()
    {
        $globalStats = $this->globalStatsService->getGlobalStats();
        $events = $this->eventService->getEventsSummary();

        return view('home', [
            'globalStats' => $globalStats,
            'events' => $events
        ]);
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

        $suggestions = Suggestion::where('tag_id', str_replace('#', '', $tag))
            ->latest()
            ->limit(5)
            ->get();

        return view('player-result', [
            'player' => $player,
            'insights' => $insights,
            'recommendations' => $recommendations,
            'suggestions' => $suggestions,
            'lastFetchedAt' => $result['last_fetched_at'],
            'source' => $result['source'],
        ]);
    }

    /**
     * API endpoint for AJAX event updates.
     */
    public function eventsSummary()
    {
        return response()->json([
            'events' => $this->eventService->getEventsSummary()
        ]);
    }
}
