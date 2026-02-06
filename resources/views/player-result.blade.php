<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $player['name'] }} | CoC Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-900 text-gray-100 min-h-screen p-4 md:p-8">
    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Header / General Info -->
        <div
            class="bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-700 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-6">
                <div
                    class="w-20 h-20 bg-gradient-to-br from-yellow-400 to-orange-600 rounded-2xl flex items-center justify-center text-3xl font-bold shadow-lg">
                    TH{{ $player['townHallLevel'] }}
                </div>
                <div>
                    <h1 class="text-3xl font-bold">{{ $player['name'] }}</h1>
                    <p class="text-gray-400 font-mono">{{ $player['tag'] }}</p>
                    @if(isset($player['clan']))
                        <p class="text-orange-400 font-semibold mt-1">{{ $player['clan']['name'] }}</p>
                    @endif
                </div>
            </div>
            <div class="text-center md:text-right">
                <p class="text-gray-400 text-sm uppercase tracking-wider mb-1">League</p>
                <p class="text-xl font-bold text-blue-400">{{ $player['league']['name'] ?? 'No League' }}</p>
            </div>
        </div>

        <!-- Home Button -->
        <div class="flex justify-start">
            <a href="{{ route('player.index') }}"
                class="text-gray-400 hover:text-white flex items-center gap-2 text-sm transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Search
            </a>
        </div>

        <!-- Heroes Section -->
        <section>
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-3">
                <span class="w-1.5 h-8 bg-orange-500 rounded-full"></span>
                Heroes
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $targetHeroes = ['Barbarian King', 'Archer Queen', 'Grand Warden', 'Royal Champion', 'Minion Prince'];
                @endphp
                @foreach($player['heroes'] as $hero)
                    @if(in_array($hero['name'], $targetHeroes))
                        <div
                            class="bg-gray-800/50 border border-gray-700 rounded-2xl p-5 hover:border-orange-500/50 transition-all group">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="font-bold text-lg group-hover:text-orange-400 transition-colors">{{ $hero['name'] }}
                                </h3>
                                <div class="bg-gray-700 px-3 py-1 rounded-lg text-sm font-bold">
                                    Lv. {{ $hero['level'] }} / {{ $hero['maxLevel'] }}
                                </div>
                            </div>

                            @php $progress = ($hero['level'] / $hero['maxLevel']) * 100; @endphp
                            <div class="w-full bg-gray-700 h-2.5 rounded-full overflow-hidden">
                                <div class="bg-gradient-to-r from-orange-500 to-yellow-400 h-full rounded-full transition-all duration-1000"
                                    style="width: {{ $progress }}%"></div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </section>

        <!-- Epic Equipment Section -->
        <section>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold flex items-center gap-3">
                    <span class="w-1.5 h-8 bg-purple-500 rounded-full"></span>
                    Epic Equipment
                </h2>
                <span class="text-xs text-gray-500 uppercase tracking-widest">(Focus: Max Lv 18+)</span>
            </div>

            @if($epicEquipment->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($epicEquipment as $item)
                        <div
                            class="flex items-center justify-between bg-gray-800 border {{ $item['level'] >= 20 ? 'border-purple-500/50 ring-1 ring-purple-500/20' : 'border-gray-700' }} rounded-xl p-4">
                            <div>
                                <h4 class="font-bold {{ $item['level'] >= 20 ? 'text-purple-400' : '' }}">{{ $item['name'] }}
                                </h4>
                                <p class="text-xs text-gray-500">Max Level: {{ $item['maxLevel'] }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                @if($item['level'] >= 20)
                                    <span
                                        class="bg-purple-500/20 text-purple-400 text-[10px] px-2 py-0.5 rounded-full font-bold uppercase">Pro</span>
                                @endif
                                <div class="text-right">
                                    <span class="block text-xl font-black text-white leading-tight">Lv.
                                        {{ $item['level'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-gray-800/30 border border-gray-700 border-dashed rounded-2xl p-12 text-center">
                    <p class="text-gray-500">No epic equipment meeting the criteria found.</p>
                </div>
            @endif
        </section>

        <footer class="border-t border-gray-800 pt-8 mt-12 pb-12 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} CoC Profile Tracker. All rights reserved.
        </footer>
    </div>
</body>

</html>