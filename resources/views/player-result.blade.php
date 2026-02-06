<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $player['name'] }} | Analisis Strategi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #0b0e14;
        }

        .glass-card {
            background: rgba(30, 41, 59, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 10px;
        }

        .shine {
            position: relative;
            overflow: hidden;
        }

        .shine::after {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.03), transparent);
            transform: rotate(45deg);
            animation: shine 3s infinite;
        }

        @keyframes shine {
            0% {
                transform: translateX(-100%) rotate(45deg);
            }

            100% {
                transform: translateX(100%) rotate(45deg);
            }
        }
    </style>
</head>

<body class="text-slate-200 min-h-screen pb-12">
    <div class="max-w-5xl mx-auto px-4 py-8 space-y-8">

        <!-- Navbar -->
        <div class="flex items-center justify-between">
            <a href="{{ route('player.home') }}"
                class="flex items-center gap-2 text-slate-400 hover:text-white transition-all text-sm font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
            <div
                class="px-4 py-1.5 rounded-full bg-slate-900 border border-slate-800 text-[10px] font-bold text-slate-500 flex items-center gap-4">
                <span>{{ $source }}</span>
                <span class="w-px h-3 bg-slate-800"></span>
                <span>DATA {{ \Carbon\Carbon::parse($lastFetchedAt)->format('H:i') }}</span>
            </div>
        </div>

        <!-- HERO SECTION: PROFILE SUMMARY -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Basic Profile -->
            <div
                class="lg:col-span-2 glass-card rounded-3xl p-6 md:p-8 flex flex-col md:flex-row items-center gap-8 shadow-2xl shine">
                <div class="relative shrink-0">
                    <div
                        class="w-24 h-24 bg-gradient-to-br from-orange-400 to-red-600 rounded-3xl flex items-center justify-center text-5xl font-black text-white shadow-xl">
                        {{ $player['townHallLevel'] }}
                    </div>
                    <div
                        class="absolute -bottom-2 left-1/2 -translate-x-1/2 bg-slate-900 px-3 py-1 rounded-full border border-slate-700 text-[10px] font-black uppercase tracking-tighter shadow-lg">
                        TH LEVEL</div>
                </div>
                <div class="text-center md:text-left">
                    <h1 class="text-3xl md:text-5xl font-black tracking-tighter mb-1">{{ $player['name'] }}</h1>
                    <p class="text-orange-500 font-mono text-lg font-bold mb-4 tracking-widest">{{ $player['tag'] }}</p>
                    <div class="flex flex-wrap justify-center md:justify-start gap-2">
                        @if(isset($player['clan']))
                            <div
                                class="flex items-center gap-2 bg-blue-500/10 text-blue-400 px-4 py-1.5 rounded-xl border border-blue-500/20 text-xs font-black uppercase">
                                <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                                {{ $player['clan']['name'] }}
                            </div>
                        @endif
                        <div
                            class="bg-slate-800 text-slate-400 px-4 py-1.5 rounded-xl border border-slate-700 text-xs font-black uppercase">
                            {{ $player['league']['name'] ?? 'TIDAK ADA LIGA' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- War Readiness Card -->
            <div
                class="glass-card rounded-3xl p-8 flex flex-col justify-between border-t border-r border-white/5 relative overflow-hidden">
                <div
                    class="absolute -top-4 -right-4 w-20 h-20 bg-{{ $insights['warReadiness']['status_id'] == 'ready' ? 'green' : ($insights['warReadiness']['status_id'] == 'semi_ready' ? 'amber' : 'red') }}-500/10 blur-3xl">
                </div>
                <div>
                    <p class="text-slate-500 text-[10px] uppercase font-bold tracking-widest mb-2">War Readiness Status
                    </p>
                    <h3
                        class="text-3xl font-black leading-tight {{ $insights['warReadiness']['status_id'] == 'ready' ? 'text-green-500' : ($insights['warReadiness']['status_id'] == 'semi_ready' ? 'text-amber-500' : 'text-red-500') }}">
                        {{ $insights['warReadiness']['label'] }}
                    </h3>
                </div>
                <p class="mt-4 text-xs text-slate-400 leading-relaxed font-medium">
                    {{ $insights['warReadiness']['reason'] }}
                </p>
            </div>
        </div>

        <!-- MAIN DASHBOARD -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- LEFT COLUMN: UPGRADE PRIORITY -->
            <div class="lg:col-span-1 space-y-8">
                <section class="space-y-4">
                    <h2 class="text-xl font-black flex items-center gap-3 px-1">
                        <span class="w-2 h-6 bg-red-500 rounded-full"></span>
                        PRIORITAS UPGRADE
                    </h2>
                    <div class="space-y-3">
                        @foreach($recommendations as $rec)
                            <div
                                class="glass-card p-5 rounded-2xl border-l-4 border-l-{{ $rec['color'] }}-500 transition-transform hover:scale-[1.02] cursor-default">
                                <div class="flex justify-between items-center mb-2">
                                    <span
                                        class="text-[10px] font-black uppercase tracking-widest text-slate-500">{{ $rec['type'] }}</span>
                                    <span
                                        class="text-[10px] font-black px-2 py-0.5 rounded-lg bg-{{ $rec['color'] }}-500/10 text-{{ $rec['color'] }}-500 border border-{{ $rec['color'] }}-500/20">
                                        {{ $rec['priority'] }}
                                    </span>
                                </div>
                                <h4 class="font-bold text-slate-200 mb-1">{{ $rec['name'] }}</h4>
                                <p class="text-[11px] text-slate-500 leading-relaxed">{{ $rec['reason'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>

                <!-- Health Summary -->
                <div
                    class="glass-card rounded-3xl p-6 text-center border-b-4 border-b-blue-600 bg-gradient-to-t from-blue-600/5 to-transparent">
                    <p class="text-[10px] text-slate-500 uppercase font-black tracking-[0.2em] mb-4">Overall Account
                        Health</p>
                    <div class="text-6xl font-black text-white mb-2">{{ $insights['health']['score'] }}%</div>
                    <div class="text-sm font-bold text-blue-400 uppercase tracking-widest">
                        {{ $insights['health']['status'] }}</div>
                </div>
            </div>

            <!-- RIGHT COLUMN: STRATEGY & DEEP DATA -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Strategy & Gear Recommendations -->
                <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Suggested War Strategy -->
                    <div class="glass-card rounded-3xl p-6 border border-blue-500/10">
                        <h3
                            class="text-xs font-black text-blue-500 uppercase tracking-widest mb-6 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14H8a4 4 0 00-4 4 1 1 0 001 1h10a1 1 0 001-1 4 4 0 00-4-4z" />
                            </svg>
                            WAR STRATEGY META
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-xl font-black text-white tracking-tight">
                                    {{ $insights['strategy']['strategy']['name'] }}</h4>
                                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                                    {{ $insights['strategy']['strategy']['description'] }}</p>
                            </div>
                            <div class="pt-4 border-t border-slate-800">
                                <span class="text-[9px] font-bold text-slate-600 uppercase tracking-widest">Recommended
                                    Spells</span>
                                <div class="mt-2 text-xs font-black text-blue-400">
                                    {{ $insights['strategy']['strategy']['spells'] }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Hero Gear Recommendations -->
                    <div class="glass-card rounded-3xl p-6 border border-purple-500/10">
                        <h3
                            class="text-xs font-black text-purple-500 uppercase tracking-widest mb-6 flex items-center gap-2">
                            HERO EQUIPMENT SET
                        </h3>
                        <div class="space-y-4 max-h-[180px] overflow-y-auto custom-scrollbar pr-2">
                            @foreach($insights['strategy']['gear'] as $gear)
                                <div class="bg-slate-900/40 p-3 rounded-2xl border border-slate-800/50">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-[10px] font-black text-purple-400">{{ $gear['hero'] }}</span>
                                    </div>
                                    <p class="text-[11px] font-bold text-slate-200">{{ $gear['best'] }}</p>
                                    <p class="text-[9px] text-slate-600 mt-1 italic">{{ $gear['reason'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                <!-- Super Troop & Hero Status -->
                <section class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Super Troop Suggestions -->
                    <div class="glass-card rounded-3xl p-6">
                        <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-6">SUPER TROOP
                            RECOMMENDATIONS</h3>
                        <div class="space-y-3">
                            @forelse($insights['strategy']['superTroops'] as $st)
                                <div
                                    class="flex items-center gap-4 bg-slate-900/30 p-3 rounded-2xl border border-slate-800/30">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-orange-500/10 flex items-center justify-center text-orange-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-200">{{ $st['name'] }}</h4>
                                        <p class="text-[9px] text-slate-600">{{ $st['reason'] }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-xs text-slate-600 italic">Belum tersedia untuk level TH
                                    Anda.</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Hero List (Compact) -->
                    <div class="glass-card rounded-3xl p-6">
                        <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-6">HERO STATUS
                        </h3>
                        <div class="space-y-4">
                            @foreach($insights['heroes']['list'] as $hero)
                                <div>
                                    <div class="flex justify-between items-center mb-1.5">
                                        <span class="text-xs font-bold text-slate-300">{{ $hero['name'] }}</span>
                                        <span class="text-[10px] font-mono text-slate-500">Lv {{ $hero['level'] }} /
                                            {{ $hero['maxLevel'] }}</span>
                                    </div>
                                    <div class="w-full h-1 bg-slate-800 rounded-full overflow-hidden">
                                        <div class="h-full bg-orange-500"
                                            style="width: {{ ($hero['level'] / $hero['maxLevel']) * 100 }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                <!-- RAW DATA COLLECTIONS (BOTTOM) -->
                <section class="space-y-6 pt-10 border-t border-slate-800/50">
                    <h2 class="text-xs font-black text-slate-600 uppercase tracking-[0.3em] text-center italic">Detailed
                        Collections</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Detailed Troops -->
                        <div class="glass-card rounded-3xl p-6 h-[350px] flex flex-col">
                            <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4">TROOPS</h3>
                            <div class="flex-grow overflow-y-auto custom-scrollbar pr-2 space-y-2">
                                @foreach($insights['troops']['list'] as $troop)
                                    <div
                                        class="flex justify-between items-center p-3 rounded-xl bg-slate-900/30 border border-slate-800/30">
                                        <span class="text-[11px] font-bold text-slate-400">{{ $troop['name'] }}</span>
                                        <span
                                            class="text-[10px] font-mono {{ $troop['status'] == 'MAX' ? 'text-green-500' : 'text-slate-600' }}">
                                            Lv {{ $troop['level'] }} / {{ $troop['maxLevel'] }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Spells & Gear (Collapsible/Scroll) -->
                        <div class="space-y-6">
                            <div class="glass-card rounded-3xl p-6 flex flex-col h-[165px]">
                                <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3">SPELLS
                                </h3>
                                <div class="overflow-y-auto custom-scrollbar pr-2 space-y-2">
                                    @foreach($insights['spells']['list'] as $spell)
                                        <div
                                            class="flex justify-between text-[10px] border-b border-slate-800 pb-1.5 last:border-0">
                                            <span class="text-slate-500">{{ $spell['name'] }}</span>
                                            <span class="text-blue-500 font-bold">Lv {{ $spell['level'] }} /
                                                {{ $spell['maxLevel'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="glass-card rounded-3xl p-6 flex flex-col h-[160px]">
                                <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3">
                                    COLLECTED GEAR</h3>
                                <div class="overflow-y-auto custom-scrollbar pr-2 space-y-2">
                                    @foreach($insights['equipment']['list'] as $item)
                                        <div class="flex justify-between text-[10px] bg-slate-900/20 p-2 rounded-lg">
                                            <span class="text-slate-500">{{ $item['name'] }}</span>
                                            <span class="text-purple-500 font-bold">Lv {{ $item['level'] }} /
                                                {{ $item['maxLevel'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <!-- Footer -->
        <footer class="pt-16 text-center">
            <p class="text-[9px] text-slate-700 uppercase tracking-[0.4em] font-black">CoC Deep Insight System &bull;
                2026</p>
        </footer>
    </div>
</body>

</html>