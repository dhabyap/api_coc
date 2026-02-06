<?php

namespace App\Http\Controllers;

use App\Services\CocPlayerService;
use App\Services\UpgradeRecommendationService;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    protected CocPlayerService $playerService;
    protected UpgradeRecommendationService $recommendationService;

    public function __construct(
        CocPlayerService $playerService,
        UpgradeRecommendationService $recommendationService
    ) {
        $this->playerService = $playerService;
        $this->recommendationService = $recommendationService;
    }

    /**
     * Show the homepage.
     */
    public function home()
    {
        return view('home');
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
        $recommendations = $this->recommendationService->getRecommendations($player);

        // Filter Epic Equipment (maxLevel >= 18)
        $epicEquipment = collect($player['heroEquipment'] ?? [])
            ->filter(function ($item) {
                return isset($item['maxLevel']) && $item['maxLevel'] >= 18;
            })
            ->sortByDesc('level')
            ->values();

        return view('player-result', [
            'player' => $player,
            'epicEquipment' => $epicEquipment,
            'recommendations' => $recommendations,
            'lastFetchedAt' => $result['last_fetched_at'],
            'source' => $result['source'],
        ]);
    }
}
