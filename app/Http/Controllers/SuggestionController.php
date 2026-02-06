<?php

namespace App\Http\Controllers;

use App\Models\Suggestion;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tag_id' => 'required|string',
            'name' => 'nullable|string|max:100',
            'suggestion' => 'required|string|max:1000',
        ]);

        $tagId = str_replace('#', '', $request->tag_id);
        $ip = $request->ip();

        // Spam Protection: Max 3 per Tag + IP per day
        $todayCount = Suggestion::where('tag_id', $tagId)
            ->where('ip_address', $ip)
            ->whereDate('created_at', now()->toDateString())
            ->count();

        if ($todayCount >= 3) {
            return back()->with('error', 'Maaf, Anda sudah mengirimkan terlalu banyak saran untuk player ini hari ini. Silakan coba lagi besok.');
        }

        Suggestion::create([
            'tag_id' => $tagId,
            'name' => $request->name,
            'suggestion' => $request->suggestion,
            'ip_address' => $ip,
        ]);

        return back()->with('success', 'Terima kasih atas sarannya! Masukan Anda sangat berarti bagi kami.');
    }
}
