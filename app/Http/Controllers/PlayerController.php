<?php

namespace App\Http\Controllers;

use App\Services\CocService;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    protected CocService $cocService;

    public function __construct(CocService $cocService)
    {
        $this->cocService = $cocService;
    }

    /**
     * Show the player search form.
     */
    public function index()
    {
        return view('player-search');
    }

    /**
     * Search for a player and show results.
     */
    public function show(Request $request)
    {
        $request->validate([
            'tag' => 'required|string|min:3',
        ]);

        $tag = $request->input('tag');

        // Normalize tag (ensure starts with #)
        if (!str_starts_with($tag, '#')) {
            $tag = '#' . $tag;
        }

        $result = $this->cocService->getPlayer($tag);

        if (!$result['success']) {
            return redirect()->route('player.index')
                ->with('error', $result['message'] ?? 'Player not found.')
                ->withInput();
        }

        $player = $result['data'];

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
        ]);
    }
}
