<?php

namespace App\Http\Controllers;

use App\Models\Suggestion;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:100',
            'suggestion' => 'required|string|max:1000',
        ]);

        Suggestion::create([
            'name' => $request->name,
            'suggestion' => $request->suggestion,
        ]);

        return back()->with('success', 'Terima kasih atas sarannya! Masukan Anda sangat berarti bagi kami.');
    }
}
