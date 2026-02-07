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
        <div class="space-y-6">
            <!-- Basic Profile -->
            <div class="relative overflow-hidden rounded-3xl group glass-card">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/20 via-slate-900 to-slate-900 z-0"></div>

                <div class="relative z-10 p-6 md:p-10 flex flex-col md:flex-row items-center gap-8 shine">
                    <div class="relative shrink-0">
                        <div
                            class="w-24 h-24 bg-gradient-to-br from-orange-400 to-red-600 rounded-[2rem] flex items-center justify-center text-5xl font-black text-white shadow-[0_0_30px_rgba(234,88,12,0.3)] transform -rotate-3">
                            {{ $player['townHallLevel'] }}
                        </div>
                        <div
                            class="absolute -bottom-2 left-1/2 -translate-x-1/2 bg-white text-slate-900 px-3 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest border-2 border-slate-900">
                            TH {{ $player['townHallLevel'] }}
                        </div>
                    </div>

                    <div class="text-center md:text-left flex-grow">
                        <h1 class="text-4xl md:text-5xl font-black tracking-tighter text-white mb-1">
                            {{ $player['name'] }}
                        </h1>
                        <p class="text-orange-500 font-mono text-lg font-bold mb-4 tracking-[0.2em]">
                            <span class="opacity-50">#</span>{{ $player['tag'] }}
                        </p>
                        <div class="flex flex-wrap justify-center md:justify-start gap-2">
                            @if(isset($player['clan']))
                                <div
                                    class="bg-white/5 px-4 py-1.5 rounded-xl border border-white/10 text-[10px] font-bold uppercase">
                                    {{ $player['clan']['name'] }}
                                </div>
                            @endif
                            <div
                                class="bg-indigo-500/10 px-4 py-1.5 rounded-xl border border-indigo-500/20 text-[10px] font-black uppercase text-indigo-400">
                                {{ $player['league']['name'] ?? 'NO LEAGUE' }}
                            </div>
                        </div>
                    </div>

                    <!-- New: Key Stats on the right -->
                    <div class="hidden md:grid grid-cols-2 gap-x-8 gap-y-4 border-l border-white/10 pl-8 shrink-0">
                        <div class="space-y-0.5">
                            <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Exp Level</p>
                            <p class="text-xl font-black text-white leading-none">
                                {{ $insights['playerStats']['level'] }}</p>
                        </div>
                        <div class="space-y-0.5">
                            <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">War Stars</p>
                            <p class="text-xl font-black text-yellow-500 leading-none">
                                {{ $insights['playerStats']['warStars'] }}</p>
                        </div>
                        <div class="space-y-0.5">
                            <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Attack Wins</p>
                            <p class="text-xl font-black text-orange-400 leading-none">
                                {{ $insights['playerStats']['attackWins'] }}</p>
                        </div>
                        <div class="space-y-0.5">
                            <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Highest Cups</p>
                            <p class="text-xl font-black text-blue-400 leading-none">
                                {{ $insights['playerStats']['bestTrophies'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Grid: Health, War, Rush -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Account Health -->
                <div class="glass-card rounded-3xl p-6 border-b-4 border-b-blue-500 relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-24 h-24 bg-blue-500/10 blur-3xl"></div>
                    <p class="text-slate-500 text-[9px] uppercase font-black tracking-widest mb-4">Account Health</p>
                    <div class="flex items-end gap-2 mb-2">
                        <span
                            class="text-5xl font-black text-white leading-none">{{ $insights['health']['score'] }}%</span>
                    </div>
                    <p class="text-xs font-bold text-blue-400 uppercase tracking-widest mb-4">
                        {{ $insights['health']['status'] }}
                    </p>
                    <div class="w-full h-1.5 bg-slate-900 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500 shadow-[0_0_10px_rgba(59,130,246,0.5)]"
                            style="width:{{ $insights['health']['score'] }}%"></div>
                    </div>
                </div>

                <!-- War Readiness -->
                <div
                    class="glass-card rounded-3xl p-6 border-b-4 border-b-{{ $insights['warReadiness']['status_id'] == 'ready' ? 'green' : ($insights['warReadiness']['status_id'] == 'semi_ready' ? 'amber' : 'red') }}-500 relative overflow-hidden">
                    <div
                        class="absolute -top-10 -right-10 w-24 h-24 bg-{{ $insights['warReadiness']['status_id'] == 'ready' ? 'green' : ($insights['warReadiness']['status_id'] == 'semi_ready' ? 'amber' : 'red') }}-500/10 blur-3xl">
                    </div>
                    <p class="text-slate-500 text-[9px] uppercase font-black tracking-widest mb-4">War Status</p>
                    <h3
                        class="text-2xl font-black text-{{ $insights['warReadiness']['status_id'] == 'ready' ? 'green' : ($insights['warReadiness']['status_id'] == 'semi_ready' ? 'amber' : 'red') }}-500 uppercase mb-2">
                        {{ $insights['warReadiness']['label'] }}
                    </h3>
                    <p class="text-[10px] text-slate-400 leading-relaxed min-h-[3rem]">
                        {{ $insights['warReadiness']['reason'] }}
                    </p>
                </div>

                <!-- Rush Status -->
                <div
                    class="glass-card rounded-3xl p-6 border-b-4 border-b-{{ $insights['rush']['isRushed'] ? 'red' : 'green' }}-500 relative overflow-hidden">
                    <div
                        class="absolute -top-10 -right-10 w-24 h-24 bg-{{ $insights['rush']['isRushed'] ? 'red' : 'green' }}-500/10 blur-3xl">
                    </div>
                    <p class="text-slate-500 text-[9px] uppercase font-black tracking-widest mb-4">Account Status</p>
                    <h3
                        class="text-2xl font-black text-{{ $insights['rush']['isRushed'] ? 'red' : 'green' }}-500 uppercase mb-2">
                        {{ $insights['rush']['status'] }}
                    </h3>
                    <p class="text-[10px] text-slate-400 leading-relaxed min-h-[3rem]">
                        {{ count($insights['rush']['reasons']) > 0 ? $insights['rush']['reasons'][0] : 'Akun Anda dalam kondisi perkembangan yang seimbang.' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- API DATA COLLECTIONS: TROOPS, SPELLS, GEAR, HEROES -->
        <section class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Detailed Troops -->
                <div class="lg:col-span-2 glass-card rounded-3xl p-6 h-[400px] flex flex-col">
                    <h3
                        class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                        TROOPS COLLECTION
                    </h3>
                    <div
                        class="flex-grow overflow-y-auto custom-scrollbar pr-2 grid grid-cols-1 md:grid-cols-2 gap-2 content-start">
                        @foreach($insights['troops']['list'] as $troop)
                            <div
                                class="flex justify-between items-center p-3 rounded-xl {{ $troop['isMax'] ? 'bg-orange-500/10 border-orange-500/30' : 'bg-slate-900/30 border-slate-800/30' }} border transition-all hover:bg-slate-900/50">
                                <span
                                    class="text-[11px] font-bold {{ $troop['isMax'] ? 'text-orange-400' : 'text-slate-400' }}">
                                    {{ $troop['name'] }}
                                    @if($troop['isMax'])
                                        <span class="ml-1 text-[8px] bg-orange-500 text-white px-1 rounded-sm">MAX</span>
                                    @endif
                                </span>
                                <span
                                    class="text-[10px] font-mono {{ $troop['isMax'] ? 'text-orange-500' : 'text-slate-600' }}">
                                    Lv {{ $troop['level'] }} / {{ $troop['maxLevel'] }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Hero Status (API Data) -->
                <div class="glass-card rounded-3xl p-6 flex flex-col h-[400px]">
                    <h3
                        class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                        HERO STATUS
                    </h3>
                    <div class="space-y-5 overflow-y-auto custom-scrollbar pr-2">
                        @foreach($insights['heroes']['list'] as $hero)
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span
                                        class="text-xs font-bold {{ (isset($hero['isMax']) && $hero['isMax']) || $hero['level'] >= $hero['maxLevel'] ? 'text-orange-400' : 'text-slate-300' }}">
                                        {{ $hero['name'] }}
                                        @if($hero['level'] >= $hero['maxLevel'])
                                            <span class="ml-1 text-[8px] bg-orange-500 text-white px-1 rounded-sm">MAX</span>
                                        @endif
                                    </span>
                                    <span class="text-[10px] font-mono text-slate-500">Lv {{ $hero['level'] }} /
                                        {{ $hero['maxLevel'] }}</span>
                                </div>
                                <div class="w-full h-1.5 bg-slate-900 rounded-full overflow-hidden">
                                    <div class="h-full {{ $hero['level'] >= $hero['maxLevel'] ? 'bg-orange-500 shadow-[0_0_15px_rgba(249,115,22,0.5)]' : 'bg-slate-600' }}"
                                        style="width: {{ ($hero['level'] / max(1, $hero['maxLevel'])) * 100 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Spells -->
                <div class="glass-card rounded-3xl p-6 flex flex-col min-h-[300px]">
                    <h3
                        class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                        SPELL COLLECTIONS
                    </h3>
                    <div class="overflow-y-auto custom-scrollbar pr-2 grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-2">
                        @foreach($insights['spells']['list'] as $spell)
                            <div
                                class="flex justify-between items-center p-3 rounded-xl {{ $spell['isMax'] ? 'bg-blue-500/10 border-blue-500/30' : 'bg-slate-900/30 border-slate-800/30' }} border transition-all text-xs">
                                <span class="{{ $spell['isMax'] ? 'text-blue-400 font-bold' : 'text-slate-400' }}">
                                    {{ $spell['name'] }}
                                    @if($spell['isMax'])
                                        <span
                                            class="ml-1 text-[8px] bg-blue-500 text-white px-1 rounded-sm uppercase">Max</span>
                                    @endif
                                </span>
                                <span class="font-mono {{ $spell['isMax'] ? 'text-blue-500' : 'text-slate-600' }}">Lv
                                    {{ $spell['level'] }} /
                                    {{ $spell['maxLevel'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- Equipment -->
                <div class="glass-card rounded-3xl p-6 flex flex-col min-h-[300px]">
                    <h3
                        class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                        COLLECTED GEAR (MAX AT TOP)
                    </h3>
                    <div class="overflow-y-auto custom-scrollbar pr-2 space-y-4">
                        <!-- Epic Gear -->
                        @php $epicGear = collect($insights['equipment']['list'])->where('isEpic', true); @endphp
                        @if($epicGear->count() > 0)
                            <div class="space-y-2">
                                <div class="flex items-center gap-2 px-1">
                                    <span class="text-[8px] font-black text-indigo-400 uppercase tracking-[0.2em]">Epic Equipment</span>
                                    <div class="h-px flex-grow bg-indigo-500/20"></div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    @foreach($epicGear as $item)
                                        <div class="flex justify-between items-center p-3 rounded-xl border-indigo-500/50 bg-indigo-500/10 shadow-[inset_0_0_15px_rgba(99,102,241,0.2)] border transition-all text-xs">
                                            <span class="flex items-center gap-1.5 text-indigo-400 font-black">
                                                <svg class="w-3 h-3 text-indigo-400" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                </svg>
                                                {{ $item['name'] }}
                                                @if($item['isMax'])
                                                    <span class="text-[8px] bg-indigo-500 text-white px-1 rounded-sm uppercase">Max</span>
                                                @endif
                                            </span>
                                            <span class="font-mono text-indigo-400/80">
                                                Lv {{ $item['level'] }} / {{ $item['maxLevel'] }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Common Gear -->
                        @php $commonGear = collect($insights['equipment']['list'])->where('isEpic', false); @endphp
                        @if($commonGear->count() > 0)
                            <div class="space-y-2">
                                <div class="flex items-center gap-2 px-1">
                                    <span class="text-[8px] font-black text-slate-500 uppercase tracking-[0.2em]">Common Equipment</span>
                                    <div class="h-px flex-grow bg-slate-800/50"></div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    @foreach($commonGear as $item)
                                        <div class="flex justify-between items-center p-3 rounded-xl {{ $item['isMax'] ? 'bg-purple-500/10 border-purple-500/30' : 'bg-slate-900/30 border-slate-800/30' }} border transition-all text-xs">
                                            <span class="flex items-center gap-1.5 {{ $item['isMax'] ? 'text-purple-400 font-bold' : 'text-slate-400' }}">
                                                {{ $item['name'] }}
                                                @if($item['isMax'])
                                                    <span class="text-[8px] bg-purple-500 text-white px-1 rounded-sm uppercase">Max</span>
                                                @endif
                                            </span>
                                            <span class="font-mono {{ $item['isMax'] ? 'text-purple-500' : 'text-slate-600' }}">
                                                Lv {{ $item['level'] }} / {{ $item['maxLevel'] }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <div class="w-full h-px bg-gradient-to-r from-transparent via-slate-800 to-transparent my-4"></div>


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

            </div>

            <!-- RIGHT COLUMN: ANALYTICS & RECOMMENDATIONS -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Super Troop Suggestions -->
                <div class="glass-card rounded-3xl p-6 border border-orange-500/10">
                    <h3
                        class="text-[10px] font-black text-orange-500 uppercase tracking-widest mb-6 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"
                                clip-rule="evenodd" />
                        </svg>
                        SUPER TROOP RECOMMENDATIONS
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @forelse($insights['strategy']['superTroops'] as $st)
                            <div class="flex items-center gap-4 bg-slate-900/30 p-3 rounded-2xl border border-slate-800/30">
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
                            <div class="col-span-2 text-center py-4 text-xs text-slate-600 italic">Belum tersedia untuk
                                level TH Anda.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Strategy & Gear Recommendations (Moved Down) -->
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
                                    {{ $insights['strategy']['strategy']['name'] }}
                                </h4>
                                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                                    {{ $insights['strategy']['strategy']['description'] }}
                                </p>
                            </div>
                            <div class="pt-4 border-t border-slate-800">
                                <span class="text-[9px] font-bold text-slate-600 uppercase tracking-widest">Recommended
                                    Spells</span>
                                <div class="mt-2 text-xs font-black text-blue-400">
                                    {{ $insights['strategy']['strategy']['spells'] }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hero Gear Recommendations -->
                    <div class="glass-card rounded-3xl p-6 border border-purple-500/10">
                        <h3
                            class="text-xs font-black text-purple-500 uppercase tracking-widest mb-6 flex items-center gap-2">
                            HERO EQUIPMENT SET</h3>
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
            </div>

        </div>

        <!-- SUGGESTIONS & FEEDBACK SECTION -->
        <section class="space-y-6 pt-10 border-t border-slate-800/50">
            <div class="flex flex-col md:flex-row justify-between items-end gap-4">
                <div>
                    <h2 class="text-2xl font-black flex items-center gap-3">
                        <span class="w-2 h-6 bg-blue-500 rounded-full"></span>
                        HUBUNGI DEVELOPER
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">Berikan saran atau laporkan bug langsung kepada pengembang
                        aplikasi.</p>
                </div>
            </div>

            @if(session('success'))
                <div
                    class="bg-green-500/10 border border-green-500/20 text-green-500 p-4 rounded-2xl text-xs font-bold animate-pulse">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-500/10 border border-red-500/20 text-red-500 p-4 rounded-2xl text-xs font-bold">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Suggestion List -->
                <div class="space-y-4">
                    <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Masukan Terbaru
                    </h3>
                    <div class="space-y-3 max-h-[400px] overflow-y-auto custom-scrollbar pr-2">
                        @forelse($suggestions as $s)
                            <div class="glass-card p-4 rounded-2xl border-l-2 border-l-blue-500/50">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-xs font-black text-slate-200">{{ $s->name ?? 'Anonim' }}</span>
                                    <span
                                        class="text-[9px] text-slate-600 font-mono">{{ $s->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-[11px] text-slate-400 leading-relaxed italic">"{{ $s->suggestion }}"</p>
                            </div>
                        @empty
                            <div
                                class="text-center py-10 bg-slate-900/20 rounded-3xl border border-dashed border-slate-800">
                                <p class="text-xs text-slate-600">Belum ada masukan untuk developer. <br> Jadilah yang
                                    pertama memberikan ide atau laporan!</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Feedback Form -->
                <div class="glass-card rounded-3xl p-6 border-t border-white/5">
                    <h3 class="text-sm font-black text-white mb-6">Kirim Masukan</h3>
                    <form action="{{ route('suggestions.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5 ml-1">Tag
                                    CoC Anda (Wajib)</label>
                                <div class="relative">
                                    <span
                                        class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-600 font-bold text-xs">#</span>
                                    <input type="text" name="tag_id" required placeholder="P8Y28RRLL"
                                        class="w-full bg-slate-900/50 border border-slate-800 rounded-xl py-2.5 pl-7 pr-4 text-xs text-slate-200 focus:outline-none focus:border-blue-500/50 transition-colors uppercase font-mono">
                                </div>
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5 ml-1">Nama
                                    (Opsional)</label>
                                <input type="text" name="name" placeholder="Contoh: Master COC"
                                    class="w-full bg-slate-900/50 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-blue-500/50 transition-colors">
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5 ml-1">Pesan
                                / Saran</label>
                            <textarea name="suggestion" required rows="4"
                                placeholder="Tuliskan ide fitur baru, laporan bug, atau saran lainnya..."
                                class="w-full bg-slate-900/50 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-blue-500/50 transition-colors resize-none"></textarea>
                        </div>

                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-500 text-white text-[11px] font-black uppercase tracking-widest py-3.5 rounded-xl transition-all shadow-lg shadow-blue-900/20 flex items-center justify-center gap-2">
                            <span>Kirim Masukan Ke Developer</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="pt-16 text-center">
            <p class="text-[9px] text-slate-700 uppercase tracking-[0.4em] font-black">CoC Deep Insight System &bull;
                2026</p>
        </footer>
    </div>
</body>

</html>