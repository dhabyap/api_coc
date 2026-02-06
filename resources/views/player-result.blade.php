<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $player['name'] }} | CoC Analysis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-900 text-gray-100 min-h-screen p-4 md:p-8">
    <div class="max-w-5xl mx-auto space-y-8 pb-20">

        <!-- Navigation -->
        <div class="flex justify-between items-center">
            <a href="{{ route('player.home') }}"
                class="text-gray-400 hover:text-white flex items-center gap-2 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                New Search
            </a>
            <div
                class="text-xs text-gray-500 uppercase tracking-widest bg-gray-800 px-4 py-2 rounded-full border border-gray-700">
                Source: <span
                    class="{{ $source == 'api' ? 'text-green-400' : 'text-blue-400' }} font-bold">{{ strtoupper($source) }}</span>
                @if($lastFetchedAt)
                    <span class="mx-2 font-mono ml-4">Updated:
                        {{ \Carbon\Carbon::parse($lastFetchedAt)->diffForHumans() }}</span>
                @endif
            </div>
        </div>

        <!-- Header -->
        <div class="bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-700">
            <div class="h-2 bg-gradient-to-r from-orange-500 to-yellow-500"></div>
            <div class="p-8 flex flex-col md:flex-row justify-between items-center gap-8">
                <div class="flex items-center gap-6">
                    <div class="relative">
                        <div
                            class="w-24 h-24 bg-gradient-to-br from-yellow-400 to-orange-600 rounded-2xl flex items-center justify-center text-4xl font-black shadow-2xl relative z-10">
                            {{ $player['townHallLevel'] }}
                        </div>
                        <div
                            class="absolute -bottom-2 -right-2 bg-gray-900 px-3 py-1 rounded-lg border border-gray-700 text-[10px] font-bold uppercase tracking-tighter z-20">
                            Town Hall</div>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold">{{ $player['name'] }}</h1>
                        <p class="text-gray-400 font-mono tracking-tight">{{ $player['tag'] }}</p>
                        @if(isset($player['clan']))
                            <div class="flex items-center gap-2 mt-2">
                                <span
                                    class="bg-orange-500/10 text-orange-500 text-xs px-2 py-1 rounded border border-orange-500/20 font-bold">CLAN</span>
                                <span class="text-gray-300 font-semibold">{{ $player['clan']['name'] }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div
                    class="text-center md:text-right bg-gray-900/50 p-6 rounded-2xl border border-gray-700 min-w-[200px]">
                    <p class="text-gray-500 text-xs uppercase tracking-widest mb-1">League Rank</p>
                    <p class="text-2xl font-black text-blue-400">{{ $player['league']['name'] ?? 'Unranked' }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Upgrade Recommendations -->
            <div class="lg:col-span-1 space-y-6">
                <h2 class="text-xl font-bold flex items-center gap-3 px-2">
                    <span class="w-1.5 h-6 bg-blue-500 rounded-full"></span>
                    Upgrade Priority
                </h2>
                <div class="space-y-4">
                    @foreach($recommendations as $rec)
                        <div class="bg-gray-800/80 border border-gray-700 p-5 rounded-2xl relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-2">
                                <span
                                    class="text-[9px] font-bold uppercase tracking-widest px-2 py-0.5 rounded {{ $rec['priority'] == 'High' ? 'bg-red-500/10 text-red-500' : 'bg-yellow-500/10 text-yellow-500' }}">
                                    {{ $rec['priority'] }}
                                </span>
                            </div>
                            <h4 class="font-bold text-gray-100 mb-1 group-hover:text-blue-400 transition-colors">
                                {{ $rec['title'] }}</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">{{ $rec['reason'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Right Column: Heroes & Equipment -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Heroes Grid -->
                <section>
                    <h2 class="text-xl font-bold mb-6 flex items-center gap-3 px-2">
                        <span class="w-1.5 h-6 bg-orange-500 rounded-full"></span>
                        Heroes Progress
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @php
                            $targetHeroes = ['Barbarian King', 'Archer Queen', 'Grand Warden', 'Royal Champion', 'Minion Prince'];
                        @endphp
                        @foreach($player['heroes'] as $hero)
                            @if(in_array($hero['name'], $targetHeroes))
                                <div class="bg-gray-800 border border-gray-700 rounded-2xl p-5">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="font-bold">{{ $hero['name'] }}</h3>
                                        <span class="text-xs font-mono text-gray-400">Lv {{ $hero['level'] }} /
                                            {{ $hero['maxLevel'] }}</span>
                                    </div>
                                    @php $progress = ($hero['level'] / $hero['maxLevel']) * 100; @endphp
                                    <div class="w-full bg-gray-900 h-2 rounded-full overflow-hidden mb-1">
                                        <div class="bg-gradient-to-r from-orange-500 to-yellow-400 h-full transition-all duration-700"
                                            style="width: {{ $progress }}%"></div>
                                    </div>
                                    @if($hero['level'] == $hero['maxLevel'])
                                        <p class="text-[9px] text-green-500 font-bold uppercase tracking-widest">MAX LEVEL</p>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                </section>

                <!-- Epic Equipment -->
                <section>
                    <h2 class="text-xl font-bold mb-6 flex items-center gap-3 px-2">
                        <span class="w-1.5 h-6 bg-purple-500 rounded-full"></span>
                        Epic Equipment Analysis
                    </h2>
                    @if($epicEquipment->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($epicEquipment as $item)
                                <div
                                    class="flex flex-col bg-gray-800 border {{ $item['level'] == $item['maxLevel'] ? 'border-green-500/30' : 'border-gray-700' }} rounded-2xl p-4 relative overflow-hidden shadow-lg">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h4 class="font-bold text-gray-100">{{ $item['name'] }}</h4>
                                            <p class="text-[10px] text-gray-500">Max Level: {{ $item['maxLevel'] }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span
                                                class="text-xl font-black {{ $item['level'] == $item['maxLevel'] ? 'text-green-400' : 'text-purple-400' }}">
                                                Lv. {{ $item['level'] }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        @if($item['level'] == $item['maxLevel'])
                                            <span
                                                class="bg-green-500/10 text-green-500 text-[10px] px-2 py-0.5 rounded-full font-bold uppercase">MAXED</span>
                                        @elseif($item['level'] >= ($item['maxLevel'] * 0.8))
                                            <span
                                                class="bg-blue-500/10 text-blue-500 text-[10px] px-2 py-0.5 rounded-full font-bold uppercase">Near
                                                Max</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-12 border-2 border-dashed border-gray-800 rounded-3xl text-center">
                            <p class="text-gray-500">No epic equipment found for this player.</p>
                        </div>
                    @endif
                </section>
            </div>
        </div>

        <footer class="pt-20 text-center border-t border-gray-800 opacity-50">
            <p class="text-[10px] text-gray-500 mb-2 max-w-2xl mx-auto">
                Official Supercell Fan Content Policy applies. This is an independent analyzer tool.
            </p>
            <p class="text-xs">&copy; {{ date('Y') }} Tracker System</p>
        </footer>
    </div>
</body>

</html>