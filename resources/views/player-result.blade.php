<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $player['name'] }} | Advanced Analysis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #0f172a;
        }

        .glass-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .gradient-border {
            position: relative;
            border-radius: 1rem;
        }

        .gradient-border::before {
            content: "";
            position: absolute;
            inset: -2px;
            border-radius: 1rem;
            padding: 2px;
            background: linear-gradient(to right, #f59e0b, #ef4444);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }
    </style>
</head>

<body class="text-slate-200 min-h-screen pb-20">
    <div class="max-w-6xl mx-auto px-4 py-8 space-y-8">

        <!-- Top Nav & Source Info -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
            <a href="{{ route('player.home') }}"
                class="group flex items-center gap-2 text-slate-400 hover:text-white transition-all">
                <span class="bg-slate-800 p-2 rounded-lg group-hover:bg-slate-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </span>
                <span class="font-medium">Return to Search</span>
            </a>
            <div
                class="flex items-center gap-3 bg-slate-800/50 px-4 py-2 rounded-xl border border-slate-700 text-xs text-slate-400">
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full {{ $source == 'api' ? 'bg-green-500' : 'bg-blue-500' }}"></span>
                    <span class="font-bold tracking-tight uppercase">{{ $source }} CACHE</span>
                </div>
                <span class="w-px h-3 bg-slate-700"></span>
                <span>Refreshed: {{ \Carbon\Carbon::parse($lastFetchedAt)->diffForHumans() }}</span>
            </div>
        </div>

        <!-- Header Summary Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Basic Info Card -->
            <div
                class="lg:col-span-2 glass-card rounded-2xl p-6 md:p-8 flex flex-col md:flex-row items-center gap-8 border-l-4 border-l-amber-500">
                <div class="relative">
                    <div
                        class="w-24 h-24 bg-gradient-to-br from-amber-400 to-orange-600 rounded-2xl flex items-center justify-center text-4xl font-extrabold text-white shadow-2xl">
                        {{ $player['townHallLevel'] }}
                    </div>
                    <div
                        class="absolute -bottom-2 left-1/2 -translate-x-1/2 bg-slate-900 px-3 py-1 rounded-full border border-slate-700 text-[9px] font-black whitespace-nowrap">
                        TOWN HALL</div>
                </div>
                <div class="flex-grow text-center md:text-left">
                    <h1 class="text-4xl font-extrabold tracking-tight mb-1">{{ $player['name'] }}</h1>
                    <p class="text-orange-500 font-mono font-bold mb-3 tracking-widest">{{ $player['tag'] }}</p>
                    <div class="flex flex-wrap justify-center md:justify-start gap-2">
                        @if(isset($player['clan']))
                            <span
                                class="bg-blue-500/10 text-blue-400 px-3 py-1 rounded-lg border border-blue-500/20 text-xs font-bold">{{ $player['clan']['name'] }}</span>
                        @endif
                        <span
                            class="bg-slate-700/50 text-slate-300 px-3 py-1 rounded-lg border border-slate-600 text-xs font-bold">{{ $player['league']['name'] ?? 'No League' }}</span>
                    </div>
                </div>
                <div class="hidden md:block w-px h-20 bg-slate-700/50"></div>
                <div class="text-center">
                    <p class="text-[10px] text-slate-500 uppercase tracking-widest mb-1">Status</p>
                    <div
                        class="text-xl font-bold {{ $insights['warReadiness']['isReady'] ? 'text-green-500' : 'text-red-500' }}">
                        {{ $insights['warReadiness']['isReady'] ? 'WAR READY' : 'PREP MODE' }}
                    </div>
                </div>
            </div>

            <!-- Health Score Card -->
            <div class="glass-card rounded-2xl p-6 flex items-center justify-between border-b-4 border-b-blue-500">
                <div class="space-y-1">
                    <p class="text-slate-500 text-xs uppercase tracking-widest font-bold">Account Health</p>
                    <h2 class="text-3xl font-black text-white">{{ $insights['health']['score'] }}%</h2>
                    <p
                        class="text-sm font-bold {{ $insights['health']['score'] >= 85 ? 'text-green-400' : ($insights['health']['score'] >= 65 ? 'text-amber-400' : 'text-red-400') }}">
                        {{ strtoupper($insights['health']['status']) }}
                    </p>
                </div>
                <div class="relative w-20 h-20">
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                        <circle class="text-slate-800" stroke-width="10" stroke="currentColor" fill="transparent" r="40"
                            cx="50" cy="50" />
                        <circle
                            class="{{ $insights['health']['score'] >= 85 ? 'text-green-500' : ($insights['health']['score'] >= 65 ? 'text-amber-500' : 'text-red-500') }}"
                            stroke-width="10" stroke-dasharray="{{ 2 * pi() * 40 }}"
                            stroke-dashoffset="{{ (1 - $insights['health']['score'] / 100) * 2 * pi() * 40 }}"
                            stroke-linecap="round" stroke="currentColor" fill="transparent" r="40" cx="50" cy="50" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Grid Layout for Analyses -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Left Panel: Stats & Suggestions -->
            <div class="lg:col-span-1 space-y-8">

                <!-- Rush Status -->
                <div
                    class="bg-gradient-to-br {{ $insights['rush']['isRushed'] ? 'from-red-500/10 to-transparent border-red-500/20' : 'from-green-500/10 to-transparent border-green-500/20' }} border p-6 rounded-2xl">
                    <h3 class="font-extrabold text-lg mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 {{ $insights['rush']['isRushed'] ? 'text-red-500' : 'text-green-500' }}"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"
                                clip-rule="evenodd" />
                        </svg>
                        Rush Detector
                    </h3>
                    <div
                        class="text-2xl font-black mb-2 {{ $insights['rush']['isRushed'] ? 'text-red-500' : 'text-green-500' }}">
                        {{ strtoupper($insights['rush']['status']) }}
                    </div>
                    @if($insights['rush']['reasons'])
                        <ul class="text-xs text-slate-400 space-y-2 mt-4">
                            @foreach($insights['rush']['reasons'] as $reason)
                                <li class="flex items-start gap-2">
                                    <span class="text-red-500 mt-1">•</span>
                                    {{ $reason }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-xs text-slate-500">Your profile metrics are well-aligned with your Town Hall level.
                        </p>
                    @endif
                </div>

                <!-- Hero Upgrade Order -->
                <div class="glass-card p-6 rounded-2xl">
                    <h3 class="font-bold text-slate-400 uppercase tracking-widest text-xs mb-6 px-1">Hero Upgrade
                        Priority</h3>
                    @if($insights['heroOrder'])
                        <div class="space-y-4">
                            @foreach($insights['heroOrder'] as $index => $hero)
                                <div class="flex items-center gap-4 group">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center font-black text-slate-500 group-hover:bg-amber-500 group-hover:text-white transition-all">
                                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                    </div>
                                    <div class="flex-grow">
                                        <div class="flex justify-between items-end mb-1">
                                            <span class="font-bold text-sm">{{ $hero['name'] }}</span>
                                            <span class="text-[10px] text-slate-500">Lv {{ $hero['level'] }} →
                                                {{ $hero['maxLevel'] }}</span>
                                        </div>
                                        <div class="w-full h-1 bg-slate-800 rounded-full overflow-hidden">
                                            <div class="h-full bg-amber-500"
                                                style="width: {{ ($hero['level'] / $hero['maxLevel']) * 100 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <span class="text-green-500 text-sm font-bold lowercase italic">All heroes are maxed!</span>
                        </div>
                    @endif
                </div>

                <!-- Upgrade Recommendations -->
                <div class="space-y-4">
                    <h3 class="font-extrabold text-lg px-2">Key Recommendations</h3>
                    @foreach($recommendations as $rec)
                        <div class="glass-card p-5 rounded-2xl relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-2">
                                <span
                                    class="text-[8px] font-black uppercase px-2 py-0.5 rounded {{ $rec['priority'] == 'High' ? 'bg-red-500/20 text-red-500' : 'bg-blue-500/20 text-blue-500' }}">
                                    {{ $rec['priority'] }}
                                </span>
                            </div>
                            <h4 class="font-bold mb-1 group-hover:text-amber-500 transition-colors">{{ $rec['title'] }}</h4>
                            <p class="text-[11px] text-slate-500 leading-relaxed">{{ $rec['reason'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Main Panel: Combat Data -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Troop Readiness Section -->
                <section class="glass-card rounded-2xl overflow-hidden p-6 md:p-8">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h2 class="text-2xl font-black">Meta Troop Readiness</h2>
                            <p class="text-xs text-slate-500 mt-1 uppercase tracking-tighter">Offensive power analysis
                                for home village</p>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-black text-amber-500">{{ $insights['troops']['readinessScore'] }}%
                            </div>
                            <p class="text-[10px] text-slate-500 uppercase">Score</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @foreach($insights['troops']['list'] as $troop)
                            <div
                                class="bg-slate-900/40 border border-slate-800 rounded-xl p-3 flex flex-col justify-between hover:border-slate-600 transition-all group">
                                <div class="flex justify-between items-start mb-2">
                                    <span
                                        class="text-[11px] font-bold text-slate-400 group-hover:text-slate-200 transition-colors">{{ $troop['name'] }}</span>
                                    @if($troop['status'] == 'MAX')
                                        <div class="w-1.5 h-1.5 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.6)]">
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="flex justify-between items-end mb-1">
                                        <span class="text-lg font-black leading-none">{{ $troop['level'] }}<span
                                                class="text-[10px] text-slate-600 font-normal">/{{ $troop['maxLevel'] }}</span></span>
                                    </div>
                                    <div class="w-full h-1 bg-slate-800 rounded-full overflow-hidden">
                                        <div class="h-full {{ $troop['status'] == 'MAX' ? 'bg-green-500' : ($troop['status'] == 'NEAR' ? 'bg-blue-500' : 'bg-slate-600') }}"
                                            style="width: {{ $troop['progress'] }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                <!-- Spells Grid -->
                <section class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="glass-card rounded-2xl p-6">
                        <h3 class="font-extrabold text-sm uppercase tracking-widest text-slate-400 mb-6">Spell Analysis
                        </h3>
                        <div class="space-y-4">
                            @foreach($insights['spells']['list'] as $spell)
                                <div>
                                    <div class="flex justify-between items-center text-xs mb-1.5">
                                        <span class="font-bold">{{ $spell['name'] }}</span>
                                        <span class="font-mono">{{ $spell['level'] }} / {{ $spell['maxLevel'] }}</span>
                                    </div>
                                    <div class="w-full h-1.5 bg-slate-900 rounded-full overflow-hidden">
                                        <div class="h-full bg-blue-500" style="width: {{ $spell['progress'] }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Epic Equipment -->
                    <div class="glass-card rounded-2xl p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-extrabold text-sm uppercase tracking-widest text-slate-400">Epic Gear</h3>
                            <span
                                class="text-2xl font-black text-purple-500">{{ $insights['equipment']['score'] }}%</span>
                        </div>
                        <div class="space-y-3">
                            @foreach(collect($insights['equipment']['list'])->take(4) as $item)
                                <div
                                    class="flex items-center justify-between bg-slate-900/50 p-3 rounded-xl border border-slate-800/50">
                                    <span class="text-xs font-medium">{{ $item['name'] }}</span>
                                    <div class="flex items-center gap-2">
                                        @if($item['level'] >= $item['maxLevel'])
                                            <span
                                                class="text-[8px] bg-green-500/10 text-green-500 px-1.5 py-0.5 rounded font-black">MAX</span>
                                        @endif
                                        <span class="font-black text-purple-400">Lv. {{ $item['level'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                <!-- Bottom Summary: Clan & Social -->
                <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="glass-card rounded-2xl p-6 flex flex-col justify-between">
                        <p class="text-[10px] text-slate-500 uppercase tracking-widest font-extrabold mb-4">Clan
                            Contribution</p>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-slate-500">Donated</p>
                                <p class="text-xl font-black">{{ $insights['clan']['donations'] }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">Capital</p>
                                <p class="text-xl font-black">{{ $insights['clan']['capital'] }}</p>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-slate-700/50 flex items-center justify-between">
                            <span class="text-xs text-slate-400 italic">Activity Level</span>
                            <span
                                class="text-xs font-bold text-blue-400 uppercase tracking-widest">{{ $insights['clan']['activity'] }}</span>
                        </div>
                    </div>

                    <div class="bg-blue-600 rounded-2xl p-6 text-white shadow-xl shadow-blue-900/20">
                        <p class="text-[10px] text-blue-200 uppercase tracking-widest font-extrabold mb-4">War Status
                        </p>
                        <h4 class="text-2xl font-black mb-2">{{ $insights['warReadiness']['status'] }}</h4>
                        <p class="text-sm text-blue-100 opacity-90 leading-relaxed">
                            {{ $insights['warReadiness']['reason'] }}</p>
                    </div>
                </section>
            </div>
        </div>

        <footer class="mt-20 pt-10 border-t border-slate-800 text-center opacity-40">
            <p class="text-[9px] mb-2">Developed for High Performance Analytics</p>
            <p class="text-[8px] text-slate-600 uppercase tracking-widest">&copy; {{ date('Y') }} COC DATA ENGINE v2.0
            </p>
        </footer>
    </div>
</body>

</html>