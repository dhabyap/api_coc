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

        .screenshot-mode .no-screenshot {
            display: none !important;
        }

        .screenshot-mode body {
            padding-bottom: 0 !important;
        }
    </style>
</head>

<body class="text-slate-200 min-h-screen pb-12">
    <div class="max-w-5xl mx-auto px-4 py-8 space-y-8">

        <!-- Navbar -->
        <div class="flex items-center justify-between no-screenshot">
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
                        <div class="flex flex-col md:flex-row md:items-center gap-3 mb-4">
                            <h1 class="text-4xl md:text-5xl font-black tracking-tighter text-white">
                                {{ $player['name'] }}
                            </h1>
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-{{ $insights['evolution']['color'] }}-500/20 border border-{{ $insights['evolution']['color'] }}-500/30 text-{{ $insights['evolution']['color'] == 'yellow' ? 'yellow-500' : ($insights['evolution']['color'] == 'purple' ? 'purple-400' : ($insights['evolution']['color'] == 'blue' ? 'blue-400' : 'red-400')) }}">
                                @if($insights['evolution']['icon'] === 'crown')
                                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M5 16L3 5L8.5 10L12 4L15.5 10L21 5L19 16H5M19 19C19 19.6 18.6 20 18 20H6C5.4 20 5 19.6 5 19V18H19V19Z" />
                                    </svg>
                                @elseif($insights['evolution']['icon'] === 'swords')
                                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M6.92 5c-.26 0-.52.1-.71.29l-1.93 1.93c-.39.39-.39 1.03 0 1.42L8.59 13l-4.14 4.14c-.39.39-.39 1.03 0 1.42l1.93 1.93c.39.39 1.03.39 1.42 0L11.94 16.34l4.14 4.14c.39.39 1.03.39 1.42 0l1.93-1.93c.39-.39.39-1.03 0-1.42L15.29 13l4.29-4.29c.39-.39.39-1.03 0-1.42l-1.93-1.93c-.39-.39-1.03-.39-1.42 0L11.94 9.66 7.63 5.29C7.44 5.1 7.18 5 6.92 5z" />
                                    </svg>
                                @elseif($insights['evolution']['icon'] === 'hammer')
                                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M19.78 6.41C19.91 6.54 20 6.7 20 6.88V7.12C20 7.3 19.91 7.46 19.78 7.59L17.59 9.78C17.46 9.91 17.3 20 17 20C16.7 20 16.54 19.91 16.41 19.78L4.22 7.59C4.09 7.46 4 7.3 4 7.12V6.88C4 6.7 4.09 6.54 4.22 6.41L6.41 4.22C6.54 4.09 6.7 4 6.88 4H7.12C7.3 4 7.46 4.09 7.59 4.22L9.78 6.41L19.78 6.41Z" />
                                    </svg>
                                @else
                                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M22.7,19L13.6,9.9C14,8.8,14,7.6,13.4,6.5c-0.8-1.4-2.2-2.3-3.8-2.5C9.4,3.9,9,4,8.6,4.1c0.1,0,0.1,0.1,0.2,0.1l3.5,3.5l-2.1,2.1L6.7,6.3C6.7,6.2,6.6,6.2,6.6,6.1C6.5,6.5,6.4,7,6.4,7.4c0.2,1.6,1.1,3,2.5,3.8c1.1,0.6,2.3,0.7,3.4,0.3L21.4,21c0.4,0.4,1,0.4,1.4,0S23.1,19.4,22.7,19z" />
                                    </svg>
                                @endif
                                <span>{{ $insights['evolution']['label'] }}</span>
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row md:items-center gap-4 mb-4">
                            <p class="text-orange-500 font-mono text-lg font-bold tracking-[0.2em]">
                                <span class="opacity-50">#</span>{{ $player['tag'] }}
                            </p>
                            <p class="text-[10px] text-slate-500 italic font-medium">
                                "{{ $insights['evolution']['description'] }}"
                            </p>
                        </div>
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
                                {{ $insights['playerStats']['level'] }}
                            </p>
                        </div>
                        <div class="space-y-0.5">
                            <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">War Stars</p>
                            <p class="text-xl font-black text-yellow-500 leading-none">
                                {{ $insights['playerStats']['warStars'] }}
                            </p>
                        </div>
                        <div class="space-y-0.5">
                            <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Attack Wins</p>
                            <p class="text-xl font-black text-orange-400 leading-none">
                                {{ $insights['playerStats']['attackWins'] }}
                            </p>
                        </div>
                        <div class="space-y-0.5">
                            <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Highest Cups</p>
                            <p class="text-xl font-black text-blue-400 leading-none">
                                {{ $insights['playerStats']['bestTrophies'] }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CV Action Bar -->
            <div class="flex justify-between items-center no-screenshot">
                <button onclick="toggleScreenshotMode()" id="screenshotToggleBtn"
                    class="px-4 py-2 rounded-xl bg-slate-800 border border-slate-700 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-white transition-all">
                    Enable Screenshot Mode
                </button>
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
                <!-- Detailed Troops Collection -->
                <div class="lg:col-span-2 glass-card rounded-3xl p-6 h-[400px] flex flex-col relative overflow-hidden">

                    @php
                        $petNames = ['L.A.S.S.I', 'Electro Owl', 'Mighty Yak', 'Unicorn', 'Frosty', 'Diggy', 'Poison Lizard', 'Phoenix', 'Spirit Fox'];
                        $siegeNames = ['Wall Wrecker', 'Battle Blimp', 'Stone Slammer', 'Siege Barracks', 'Log Launcher', 'Flame Flinger', 'Flame Finger', 'Battle Drill'];

                        $allTroops = collect($insights['troops']['list']);

                        $pets = $allTroops->filter(function ($t) use ($petNames) {
                            return in_array($t['name'], $petNames);
                        })->values();

                        $sieges = $allTroops->filter(function ($t) use ($siegeNames) {
                            return in_array($t['name'], $siegeNames);
                        })->values();

                        $troops = $allTroops->filter(function ($t) use ($petNames, $siegeNames) {
                            return !in_array($t['name'], $petNames) && !in_array($t['name'], $siegeNames);
                        })->values();
                    @endphp

                    <div class="overflow-y-auto custom-scrollbar pr-2 h-full space-y-8">

                        <!-- 1. TROOPS SECTION -->
                        <div>
                            <h3
                                class="sticky top-0 bg-[#0b0e14]/90 backdrop-blur-md z-10 py-2 text-[11px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2 border-b border-white/5 mb-3">
                                <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                                TROOPS
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach($troops as $troop)
                                    <div
                                        class="flex justify-between items-center p-3 rounded-xl {{ $troop['isMax'] ? 'bg-orange-500/10 border-orange-500/30' : 'bg-slate-900/40 border-slate-800/50' }} border transition-all hover:bg-slate-900/60 group">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="text-[11px] font-bold {{ $troop['isMax'] ? 'text-orange-400' : 'text-slate-300' }} group-hover:text-white transition-colors">
                                                {{ $troop['name'] }}
                                            </span>
                                            @if($troop['isSuper'])
                                                <span
                                                    class="text-[8px] font-black {{ $troop['isMax'] ? 'bg-orange-500 text-white' : 'bg-slate-700 text-slate-400' }} px-1.5 py-0.5 rounded uppercase tracking-wider">Super</span>
                                            @endif
                                        </div>

                                        <div class="flex items-center gap-2">
                                            @if($troop['isMax'])
                                                <span
                                                    class="text-[8px] font-black text-orange-500 bg-orange-500/10 px-1.5 py-0.5 rounded border border-orange-500/20">MAX</span>
                                            @else
                                                @if(!$troop['isSuper'])
                                                    <span class="text-[10px] font-mono text-slate-500">Lv
                                                        {{ $troop['level'] }}</span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- 2. PETS SECTION -->
                        @if($pets->isNotEmpty())
                            <div>
                                <h3
                                    class="sticky top-0 bg-[#0b0e14]/90 backdrop-blur-md z-10 py-2 text-[11px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2 border-b border-white/5 mb-3">
                                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-500"></span>
                                    PETS
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    @foreach($pets as $pet)
                                        <div
                                            class="flex justify-between items-center p-3 rounded-xl {{ $pet['isMax'] ? 'bg-cyan-500/10 border-cyan-500/30' : 'bg-slate-900/40 border-slate-800/50' }} border transition-all hover:bg-slate-900/60 group">
                                            <span
                                                class="text-[11px] font-bold {{ $pet['isMax'] ? 'text-cyan-400' : 'text-slate-300' }} group-hover:text-white transition-colors">
                                                {{ $pet['name'] }}
                                            </span>
                                            <div class="flex items-center gap-2">
                                                @if($pet['isMax'])
                                                    <span
                                                        class="text-[8px] font-black text-cyan-400 bg-cyan-500/10 px-1.5 py-0.5 rounded border border-cyan-500/20">MAX</span>
                                                @endif
                                                <span
                                                    class="text-[10px] font-mono {{ $pet['isMax'] ? 'text-cyan-500' : 'text-slate-500' }}">Lv
                                                    {{ $pet['level'] }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- 3. SIEGE MACHINES SECTION -->
                        @if($sieges->isNotEmpty())
                            <div>
                                <h3
                                    class="sticky top-0 bg-[#0b0e14]/90 backdrop-blur-md z-10 py-2 text-[11px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2 border-b border-white/5 mb-3">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                                    SIEGE MACHINES
                                </h3>
                                <div class="grid grid-cols-2 lg:grid-cols-3 gap-2">
                                    @foreach($sieges as $siege)
                                        <div
                                            class="p-2.5 rounded-xl {{ $siege['isMax'] ? 'bg-slate-700/30 border-slate-500/30' : 'bg-slate-900/40 border-slate-800/50' }} border transition-all hover:bg-slate-800/50 flex flex-col items-center text-center gap-1 group">
                                            <span
                                                class="text-[10px] font-bold {{ $siege['isMax'] ? 'text-slate-200' : 'text-slate-400' }} group-hover:text-white truncate w-full">
                                                {{ $siege['name'] }}
                                            </span>
                                            <div class="flex items-center gap-1.5">
                                                @if($siege['isMax'])
                                                    <span
                                                        class="text-[7px] font-black text-slate-300 bg-slate-500/20 px-1 rounded">MAX</span>
                                                @else
                                                    <span class="text-[9px] font-mono text-slate-600">Lv
                                                        {{ $siege['level'] }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

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
                            <div class="bg-slate-900/40 p-4 rounded-2xl border border-white/5">
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
                                <div class="w-full h-1.5 bg-slate-900 rounded-full overflow-hidden mb-3">
                                    <div class="h-full {{ $hero['level'] >= $hero['maxLevel'] ? 'bg-orange-500 shadow-[0_0_15px_rgba(249,115,22,0.5)]' : 'bg-slate-600' }}"
                                        style="width: {{ ($hero['level'] / max(1, $hero['maxLevel'])) * 100 }}%"></div>
                                </div>

                                @if(isset($hero['activeEquipment']) && count($hero['activeEquipment']) > 0)
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach($hero['activeEquipment'] as $eq)
                                            <div
                                                class="flex items-center gap-2 bg-white/5 px-3 py-2 rounded-xl border border-white/10 shadow-sm transition-all hover:bg-white/10">
                                                <div
                                                    class="w-1.5 h-1.5 rounded-full bg-purple-500 shadow-[0_0_8px_rgba(168,85,247,0.5)]">
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-[10px] font-black text-slate-200 leading-tight truncate">
                                                        {{ $eq['name'] }}
                                                    </p>
                                                    <p class="text-[8px] font-mono text-slate-500 mt-0.5">Lv {{ $eq['level'] }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
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
                                class="flex justify-between items-center p-3 rounded-xl {{ $spell['level'] >= $spell['maxLevel'] ? 'bg-blue-500/10 border-blue-500/30' : 'bg-slate-900/30 border-slate-800/30' }} border transition-all text-xs">
                                <span
                                    class="{{ $spell['level'] >= $spell['maxLevel'] ? 'text-blue-400 font-bold' : 'text-slate-400' }}">
                                    {{ $spell['name'] }}
                                    @if($spell['level'] >= $spell['maxLevel'])
                                        <span
                                            class="ml-1 text-[8px] bg-blue-500 text-white px-1 rounded-sm uppercase">Max</span>
                                    @endif
                                </span>
                                <span
                                    class="font-mono {{ $spell['level'] >= $spell['maxLevel'] ? 'text-blue-500' : 'text-slate-600' }}">Lv
                                    {{ $spell['level'] }} /
                                    {{ $spell['maxLevel'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- Equipment Collection (Grouped by Hero) -->
                <div class="glass-card rounded-3xl p-6 flex flex-col min-h-[400px] relative overflow-hidden">
                    <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                        HERO EQUIPMENT
                    </h3>
                    
                    @php
                        // User Provided Gear Metadata
                        $gearMeta = [
                            "Archer Puppet" => ["url" => "https://assets.clashk.ing/home-base/equipment-pics/Hero_Equipment_AQ_Archer_Puppet.png", "hero" => "Archer Queen", "rarity" => 1],
                            "Frozen Arrow" => ["url" => "https://assets.clashk.ing/home-base/equipment-pics/Hero_Equipment_AQ_Frozen_Arrow.png", "hero" => "Archer Queen", "rarity" => 2],
                            "Giant Arrow" => ["url" => "https://assets.clashk.ing/home-base/equipment-pics/Hero_Equipment_AQ_Giant_Arrow.png", "hero" => "Archer Queen", "rarity" => 1],
                            "Healer Puppet" => ["url" => "https://assets.clashk.ing/home-base/equipment-pics/Hero_Equipment_AQ_Healer_Puppet.png", "hero" => "Archer Queen", "rarity" => 1],
                            "Invisibility Vial" => ["url" => "https://assets.clashk.ing/home-base/equipment-pics/Hero_Equipment_AQ_Invisibility_Vial.png", "hero" => "Archer Queen", "rarity" => 1],
                            "Magic Mirror" => ["url" => "https://assets.clashk.ing/home-base/equipment-pics/Hero_Equipment_AQ_Magic_Mirror.png", "hero" => "Archer Queen", "rarity" => 2],
                            "Action Figure" => ["url" => "https://assets.clashk.ing/home-base/equipment-pics/Hero_Equipment_AQ_WWEActionFigure.png", "hero" => "Archer Queen", "rarity" => 2],
                            
                            "Barbarian Puppet" => ["url" => "https://assets.clashk.ing/home-base/equipment-pics/Hero_Equipment_BK_Barbarian_Puppet.png", "hero" => "Barbarian King", "rarity" => 1],
                            "Earthquake Boots" => ["url" => "https://assets.clashk.ing/home-base/equipment-pics/Hero_Equipment_BK_Earthquake_Boots.png", "hero" => "Barbarian King", "rarity" => 1],
                            "Giant Gauntlet" => ["url" => "https://assets.clashk.ing/home-base/equipment-pics/Hero_Equipment_BK_Giant_Gauntlet.png", "hero" => "Barbarian King", "rarity" => 2],
                            "Rage Vial" => ["url" => "https://assets.clashk.ing/home-base/equipment-pics/Hero_Equipment_BK_Rage_Vial.png", "hero" => "Barbarian King", "rarity" => 1],
                            "Vampstache" => ["url" => "https://assets.clashk.ing/home-base/equipment-pics/Hero_Equipment_BK_Vampstache.png", "hero" => "Barbarian King", "rarity" => 1],
                            "Spiky Ball" => ["url" => "https://assets.clashk.ing/home-base/equipment-pics/Hero_Equipment_BK_Spiky_Ball.png", "hero" => "Barbarian King", "rarity" => 2],
                            "Snake Bracelet" => ["url" => "https://assets.clashk.ing/home-base/equipment-pics/Hero_Equipment_BK_Snake_Bracelet.png", "hero" => "Barbarian King", "rarity" => 2],
                            
                            "Eternal Tome" => ["url" => "https://assets.clashk.ing/home-base/equipment-pics/Hero_Equipment_GW_Eternal_Tome.png", "hero" => "Grand Warden", "rarity" => 1],
                            "Fireball" => ["url" => "https://assets.clashk.ing/home-base/equipment-pics/Hero_Equipment_GW_Fireball.png", "hero" => "Grand Warden", "rarity" => 2],
                            "Healing Tome" => ["url" => "https://assets.clashk.ing/home-base/equipment-pics/Hero_Equipment_GW_Healing_Tome.png", "hero" => "Grand Warden", "rarity" => 1],
                            "Life Gem" => ["url" => "https://assets.clashk.ing/home-base/equipment-pics/Hero_Equipment_GW_Life_Gem.png", "hero" => "Grand Warden", "rarity" => 1],
                            "Rage Gem" => ["url" => "https://assets.clashk.ing/home-base/equipment-pics/Hero_Equipment_GW_Rage_Gem.png", "hero" => "Grand Warden", "rarity" => 1],
                            "Lavaloon Puppet" => ["url" => "https://assets.clashk.ing/home-base/equipment-pics/Hero_Equipment_GW_Lavaloon_Puppet.png", "hero" => "Grand Warden", "rarity" => 2],
                            
                            "Dark Orb" => ["url" => "https://assets.clashk.ing/home-base/equipment-pics/Hero_Equipment_MP_Dark_Orb.png", "hero" => "Minion Prince", "rarity" => 1],
                            "Henchmen Puppet" => ["url" => "https://assets.clashk.ing/home-base/equipment-pics/Hero_Equipment_MP_Henchman.png", "hero" => "Minion Prince", "rarity" => 1],
                            "Metal Pants" => ["url" => "https://assets.clashk.ing/home-base/equipment-pics/Hero_Equipment_MP_Metal_Pants.png", "hero" => "Minion Prince", "rarity" => 1],
                            "Noble Iron" => ["url" => "https://assets.clashk.ing/home-base/equipment-pics/Hero_Equipment_MP_Noble_Iron.png", "hero" => "Minion Prince", "rarity" => 1],
                            "Dark Crown" => ["url" => "https://assets.clashk.ing/home-base/equipment-pics/Hero_Equipment_MP_Dark_Crown.png", "hero" => "Minion Prince", "rarity" => 2],
                            
                            "Haste Vial" => ["url" => "https://assets.clashk.ing/home-base/equipment-pics/Hero_Equipment_RC_Haste_Vial.png", "hero" => "Royal Champion", "rarity" => 1],
                            "Hog Rider Puppet" => ["url" => "https://assets.clashk.ing/home-base/equipment-pics/Hero_Equipment_RC_Hog_Rider_Doll.png", "hero" => "Royal Champion", "rarity" => 1],
                            "Royal Gem" => ["url" => "https://assets.clashk.ing/home-base/equipment-pics/Hero_Equipment_RC_Royal_Gem.png", "hero" => "Royal Champion", "rarity" => 1],
                            "Seeking Shield" => ["url" => "https://assets.clashk.ing/home-base/equipment-pics/Hero_Equipment_RC_Seeking_Shield.png", "hero" => "Royal Champion", "rarity" => 1],
                            "Rocket Spear" => ["url" => "https://assets.clashk.ing/home-base/equipment-pics/Hero_Equipment_RC_Rocket_Spear.png", "hero" => "Royal Champion", "rarity" => 2],
                            "Electro Boots" => ["url" => "https://assets.clashk.ing/home-base/equipment-pics/Hero_Equipment_RC_Electro_Boots.png", "hero" => "Royal Champion", "rarity" => 2],
                        ];
                        
                        $groupedGear = collect($insights['equipment']['list'])->map(function($item) use ($gearMeta) {
                            $meta = $gearMeta[$item['name']] ?? null;
                            $item['hero'] = $meta['hero'] ?? 'Unknown';
                            $item['imgUrl'] = $meta['url'] ?? null;
                            $item['rarityLevel'] = $meta['rarity'] ?? ($item['isEpic'] ? 2 : 1); // 2=Epic, 1=Common
                            return $item;
                        })->groupBy('hero');
                        
                        // Sort Order: King, Queen, Warden, RC, Prince, Unknown
                        $heroOrder = ['Barbarian King', 'Archer Queen', 'Grand Warden', 'Royal Champion', 'Minion Prince', 'Unknown'];
                    @endphp

                    <div class="overflow-y-auto custom-scrollbar pr-2 h-full space-y-6">
                        @foreach($heroOrder as $heroName)
                            @if(isset($groupedGear[$heroName]))
                                <div>
                                    <h4 class="sticky top-0 bg-[#0b0e14]/90 backdrop-blur-md z-10 py-1.5 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-white/5 mb-2">
                                        {{ $heroName }}
                                    </h4>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                        @foreach($groupedGear[$heroName]->sortByDesc('rarityLevel')->sortByDesc('level') as $item)
                                            <div class="relative p-2 rounded-xl border {{ $item['rarityLevel'] == 2 ? 'bg-purple-500/10 border-purple-500/30' : 'bg-slate-900/40 border-slate-800/50' }} transition-all group hover:scale-[1.02]">
                                                
                                                <!-- Rarity Indicator -->
                                                @if($item['rarityLevel'] == 2)
                                                    <div class="absolute -top-1 -right-1 z-10">
                                                        <span class="bg-purple-500 text-[6px] font-black text-white px-1.5 py-0.5 rounded-full uppercase shadow-[0_0_8px_rgba(168,85,241,0.5)]">EPIC</span>
                                                    </div>
                                                @endif
                                                
                                                <div class="flex flex-col items-center gap-2">
                                                    <!-- Image -->
                                                    <div class="w-10 h-10 {{ $item['rarityLevel'] == 2 ? 'bg-purple-500/20' : 'bg-slate-700/20' }} rounded-lg p-1 flex items-center justify-center relative overflow-hidden">
                                                        @if($item['imgUrl'])
                                                            <img src="{{ $item['imgUrl'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-contain filter drop-shadow-lg">
                                                        @else
                                                            <div class="text-[8px] text-slate-500 text-center leading-tight">{{ $item['name'] }}</div>
                                                        @endif
                                                    </div>
                                                    
                                                    <!-- Info -->
                                                    <div class="w-full text-center">
                                                        <div class="text-[9px] font-bold {{ $item['rarityLevel'] == 2 ? 'text-purple-300' : 'text-slate-300' }} truncate w-full mb-0.5">
                                                            {{ $item['name'] }}
                                                        </div>
                                                        <div class="flex items-center justify-center gap-1">
                                                            @if($item['isMax'])
                                                                <span class="text-[7px] font-black text-white {{ $item['rarityLevel'] == 2 ? 'bg-purple-500' : 'bg-blue-500' }} px-1 rounded">MAX</span>
                                                            @endif
                                                            <span class="text-[8px] font-mono text-slate-500">Lv {{ $item['level'] }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <div class="w-full h-px bg-gradient-to-r from-transparent via-slate-800 to-transparent my-4"></div>


        <!-- MAIN DASHBOARD -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- LEFT COLUMN: UPGRADE PRIORITY -->
            <div class="lg:col-span-1 space-y-8">
                <section class="space-y-8">
                    <div class="flex items-center gap-3">
                        <div class="w-1.5 h-6 bg-red-500 rounded-full shadow-[0_0_12px_rgba(239,68,68,0.5)]"></div>
                        <h2 class="text-xl font-black tracking-tight text-white uppercase">Saran Upgrade</h2>
                    </div>

                    <!-- 1. HERO SECTION -->
                    @if(count($recommendations['heroes']) > 0)
                        <div class="space-y-3">
                            <span class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] px-1">Prioritas
                                Hero</span>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($recommendations['heroes'] as $rec)
                                    <div
                                        class="glass-card p-3.5 rounded-2xl border-l-[3px] border-l-red-500 transition-all hover:bg-white/5">
                                        <div class="flex justify-between items-center mb-1">
                                            <h4 class="text-xs font-black text-slate-300">{{ $rec['name'] }}</h4>
                                            <span class="text-[8px] font-bold text-red-500/80">{{ $rec['priority'] }}</span>
                                        </div>
                                        <p class="text-[10px] text-slate-500 leading-snug">{{ $rec['reason'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- 2. GEAR SECTION -->
                    @if(count($recommendations['gear']) > 0)
                        <div class="space-y-3">
                            <span class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] px-1">Prioritas
                                Equipment</span>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($recommendations['gear'] as $gp)
                                    <div
                                        class="glass-card p-3.5 rounded-2xl border-l-[3px] border-l-indigo-500 transition-all hover:bg-white/5">
                                        <div class="flex justify-between items-center mb-1">
                                            <h4 class="text-xs font-black text-indigo-400/90">{{ $gp['name'] }}</h4>
                                            <span
                                                class="bg-indigo-500 text-white text-[8px] px-1.5 py-0.5 rounded font-black">{{ $gp['rank'] }}</span>
                                        </div>
                                        <p class="text-[10px] text-slate-500 leading-snug">{{ $gp['reason'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- 3. TROOPS & SPELLS SECTION -->
                    @if(count($recommendations['troops']) > 0 || count($recommendations['spells']) > 0)
                        <div class="space-y-3">
                            <span class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] px-1">Pasukan &
                                Mantra</span>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach(array_merge($recommendations['troops'], $recommendations['spells']) as $rec)
                                    <div
                                        class="glass-card p-3.5 rounded-2xl border-l-[3px] border-l-amber-500 transition-all hover:bg-white/5">
                                        <div class="flex justify-between items-center mb-1">
                                            <h4 class="text-xs font-black text-slate-300">{{ $rec['name'] }}</h4>
                                            <span class="text-[8px] font-bold text-amber-500/80">UP</span>
                                        </div>
                                        <p class="text-[10px] text-slate-500 leading-snug">{{ $rec['reason'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
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
                            <div
                                class="flex items-center gap-4 bg-orange-500/10 p-3 rounded-2xl border border-orange-500/30 shadow-lg shadow-orange-500/5">
                                <div class="relative">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-orange-500/20 flex items-center justify-center text-orange-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <span class="absolute -top-1 -right-1 flex h-2 w-2">
                                        <span
                                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-orange-500"></span>
                                    </span>
                                </div>
                                <div class="flex-grow">
                                    <div class="flex justify-between items-center">
                                        <h4 class="text-xs font-bold text-orange-400">{{ $st['name'] }}</h4>
                                        <span
                                            class="text-[7px] font-black bg-orange-500 text-white px-1.5 py-0.5 rounded-full uppercase">MAX</span>
                                    </div>
                                    <p class="text-[9px] text-slate-500 mt-0.5">{{ $st['reason'] }}</p>
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
        <!-- CLAN RESUME / CV SECTION (WAR FOCUSED) -->
        <section id="clan-cv" class="mt-12 mb-10">
            <div class="flex items-center gap-3 mb-6 no-screenshot">
                <span class="w-2 h-6 bg-orange-500 rounded-full"></span>
                <h2 class="text-2xl font-black text-white italic tracking-tighter uppercase">War Focused Player Resume
                </h2>
                <span id="screenshot-indicator"
                    class="hidden text-[10px] bg-orange-500/10 text-orange-500 px-3 py-1 rounded-full uppercase font-black tracking-widest ml-auto border border-orange-500/20 animate-pulse">Screenshot
                    Mode Active</span>
            </div>

            <div class="relative group">
                <div
                    class="absolute -inset-1 bg-gradient-to-r from-orange-600/20 to-indigo-900/20 rounded-[2.5rem] blur opacity-40">
                </div>

                <div
                    class="relative bg-[#0d1117] rounded-[2.5rem] p-8 md:p-12 border border-white/5 overflow-hidden shadow-2xl">
                    <!-- Background Branding Decor -->
                    <div
                        class="absolute -top-10 -right-10 p-8 opacity-[0.03] select-none pointer-events-none transform rotate-12">
                        <h1 class="text-[12rem] font-black italic">WAR READY</h1>
                    </div>

                    <div class="relative z-10">
                        <!-- Header: Name, Tag, Badges and Health Ring -->
                        <div class="flex flex-col lg:flex-row justify-between items-center gap-12 mb-12">
                            <div class="flex flex-col items-center lg:items-start text-center lg:text-left">
                                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2 mb-6">
                                    <div
                                        class="px-4 py-1.5 rounded-full bg-{{ $insights['cv']['health']['color'] }}-500/10 border border-{{ $insights['cv']['health']['color'] }}-500/20 text-[10px] font-black text-{{ $insights['cv']['health']['color'] }}-400 uppercase tracking-[0.2em] flex items-center gap-2">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" />
                                        </svg>
                                        {{ $insights['cv']['health']['label'] }}
                                    </div>

                                    @if($insights['cv']['badges']['fullyMaxed'])
                                        <div
                                            class="px-4 py-1.5 rounded-full bg-indigo-500/20 border border-indigo-500/30 text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em] shadow-[0_0_15px_rgba(99,102,241,0.3)]">
                                            WAR-READY MAX ACCOUNT
                                        </div>
                                    @else
                                        @if($insights['cv']['badges']['heroMax'])
                                            <div
                                                class="px-3 py-1.5 rounded-full bg-green-500/10 border border-green-500/20 text-[9px] font-black text-green-400 uppercase tracking-widest">
                                                ALL HERO MAX</div>
                                        @endif
                                        @if($insights['cv']['badges']['troopMax'])
                                            <div
                                                class="px-3 py-1.5 rounded-full bg-orange-500/10 border border-orange-500/20 text-[9px] font-black text-orange-400 uppercase tracking-widest">
                                                CORE TROOPS MAX</div>
                                        @endif
                                    @endif
                                </div>

                                <h3
                                    class="text-6xl md:text-7xl font-black text-white mb-2 tracking-tighter leading-none">
                                    {{ $player['name'] }}
                                </h3>
                                <p class="text-orange-500 font-mono text-2xl font-bold tracking-[0.4em] mb-10">
                                    <span class="opacity-30">#</span>{{ $player['tag'] }}
                                </p>

                                <div class="grid grid-cols-2 gap-12">
                                    <div class="space-y-1">
                                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em]">Town
                                            Hall</p>
                                        <p class="text-4xl font-black text-white italic">TH
                                            {{ $player['townHallLevel'] }}
                                        </p>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em]">
                                            Evaluation</p>
                                        <p
                                            class="text-4xl font-black text-{{ $insights['cv']['health']['color'] }}-500 uppercase italic">
                                            {{ $insights['cv']['health']['score'] }}%
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Circular Health Indicator -->
                            <div class="relative w-56 h-56 md:w-64 md:h-64 group/ring">
                                <div
                                    class="absolute inset-0 bg-{{ $insights['cv']['health']['color'] }}-500/5 rounded-full blur-2xl group-hover/ring:bg-{{ $insights['cv']['health']['color'] }}-500/10 transition-all">
                                </div>
                                <svg class="w-full h-full -rotate-90 relative z-10" viewBox="0 0 36 36">
                                    <circle cx="18" cy="18" r="15.915" fill="none" class="stroke-slate-800/30"
                                        stroke-width="2.5" />
                                    <circle cx="18" cy="18" r="15.915" fill="none"
                                        class="stroke-{{ $insights['cv']['health']['color'] }}-500 transition-all duration-1000 ease-out"
                                        stroke-width="2.5"
                                        stroke-dasharray="{{ $insights['cv']['health']['score'] }}, 100"
                                        stroke-linecap="round" />
                                </svg>
                                <div class="absolute inset-0 flex flex-col items-center justify-center z-20">
                                    <span
                                        class="text-6xl md:text-7xl font-black text-white leading-none tracking-tighter">{{ $insights['cv']['health']['score'] }}<span
                                            class="text-3xl text-{{ $insights['cv']['health']['color'] }}-500 opacity-80">%</span></span>
                                    <span
                                        class="text-[10px] font-black text-slate-500 uppercase tracking-[0.5em] mt-3">Health
                                        Status</span>
                                </div>
                            </div>
                        </div>

                        <!-- Data Grid: Hero, Troops, Spell, Gear -->
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-12">
                            <!-- Hero Section (40%) -->
                            <div class="bg-slate-900/30 p-6 rounded-[2rem] border border-white/5 flex flex-col">
                                <h4
                                    class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-6 flex items-center justify-between">
                                    HERO STRENGTH
                                    @if($insights['cv']['badges']['heroMax'])
                                        <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                </h4>
                                <div class="space-y-4 flex-grow">
                                    @foreach($insights['cv']['heroes'] as $hero)
                                        <div class="space-y-1.5">
                                            <div class="flex justify-between items-center">
                                                <span
                                                    class="text-[11px] font-bold {{ $hero['isMax'] ? 'text-orange-400' : 'text-slate-300' }}">{{ $hero['name'] }}</span>
                                                <span
                                                    class="text-[10px] font-mono text-slate-500">{{ $hero['level'] }}/{{ $hero['maxLevel'] }}</span>
                                            </div>
                                            <div class="w-full h-1 bg-slate-800 rounded-full overflow-hidden">
                                                <div class="h-full bg-{{ $hero['isMax'] ? 'orange-500' : 'slate-600' }}"
                                                    style="width: {{ $hero['progress'] }}%"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-6 pt-4 border-t border-white/5 text-center">
                                    <span class="text-[9px] font-black text-slate-600 uppercase tracking-widest">Weight:
                                        40%</span>
                                </div>
                            </div>

                            <!-- Core Troop Status (25%) -->
                            <div class="bg-slate-900/30 p-6 rounded-[2rem] border border-white/5 flex flex-col">
                                <h4
                                    class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-6 flex items-center justify-between">
                                    CORE WAR TROOPS
                                    @if($insights['cv']['badges']['troopMax'])
                                        <svg class="w-4 h-4 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                </h4>
                                <div class="grid grid-cols-1 gap-2 flex-grow">
                                    @foreach($insights['cv']['troops'] as $t)
                                        <div
                                            class="flex items-center justify-between p-2 rounded-xl {{ $t['isMax'] ? 'bg-orange-500/5' : '' }}">
                                            <span
                                                class="text-[10px] font-bold {{ $t['isMax'] ? 'text-orange-400' : 'text-slate-400' }}">{{ $t['name'] }}</span>
                                            <span
                                                class="text-[8px] font-black px-2 py-0.5 rounded {{ $t['isMax'] ? 'bg-orange-500 text-white' : 'bg-slate-800 text-slate-500' }}">
                                                {{ $t['isMax'] ? 'MAX' : 'Lvl ' . $t['level'] }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-6 pt-4 border-t border-white/5 text-center">
                                    <span class="text-[9px] font-black text-slate-600 uppercase tracking-widest">Weight:
                                        25%</span>
                                </div>
                            </div>

                            <!-- War Spell Summary (15%) -->
                            <div class="bg-slate-900/30 p-6 rounded-[2rem] border border-white/5 flex flex-col">
                                <h4
                                    class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-6 flex items-center justify-between">
                                    WAR SPELLS
                                    @if($insights['cv']['badges']['spellMax'])
                                        <span class="text-[8px] bg-blue-500 text-white px-2 py-0.5 rounded font-black">ALL
                                            MAX</span>
                                    @endif
                                </h4>
                                <div class="space-y-3 flex-grow">
                                    @foreach($insights['cv']['spells'] as $s)
                                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-800/20">
                                            <div class="flex items-center gap-2">
                                                <div
                                                    class="w-1.5 h-1.5 rounded-full bg-{{ $s['isMax'] ? 'blue-500' : 'slate-700' }}">
                                                </div>
                                                <span
                                                    class="text-[10px] font-bold text-slate-300">{{ str_replace(' Spell', '', $s['name']) }}</span>
                                            </div>
                                            <span
                                                class="text-[8px] font-mono {{ $s['isMax'] ? 'text-blue-400' : 'text-slate-500' }}">
                                                {{ $s['isMax'] ? 'MAX' : 'Lvl ' . $s['level'] }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-6 pt-4 border-t border-white/5 text-center">
                                    <span class="text-[9px] font-black text-slate-600 uppercase tracking-widest">Weight:
                                        15%</span>
                                </div>
                            </div>

                            <!-- Hero Gear / Equipment (20%) -->
                            <div class="bg-slate-900/30 p-6 rounded-[2rem] border border-white/5 flex flex-col">
                                <h4
                                    class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-6 flex items-center justify-between">
                                    EQUIPMENT & GEAR
                                    @if($insights['cv']['badges']['gearMax'])
                                        <span
                                            class="bg-purple-500/10 text-purple-400 text-[8px] px-2 py-0.5 rounded font-black">MAXED</span>
                                    @endif
                                </h4>
                                <div class="flex-grow flex flex-col items-center justify-center text-center">
                                    <div class="text-[2.5rem] font-black text-white leading-none mb-1">
                                        {{ $insights['cv']['equipment']['score'] }}%
                                    </div>
                                    <div class="text-[10px] font-black text-purple-400 uppercase tracking-[0.2em]">Total
                                        Gear Progress</div>

                                    <div class="grid grid-cols-2 gap-3 mt-8 w-full">
                                        <div class="bg-slate-800/40 p-3 rounded-2xl border border-white/5">
                                            <p class="text-[8px] font-black text-slate-500 uppercase mb-1">Status</p>
                                            <p
                                                class="text-[10px] font-bold {{ $insights['cv']['badges']['gearMax'] ? 'text-green-500' : 'text-orange-400' }}">
                                                {{ $insights['cv']['badges']['gearMax'] ? 'Optimized' : 'In Progress' }}
                                            </p>
                                        </div>
                                        <div class="bg-slate-800/40 p-3 rounded-2xl border border-white/5">
                                            <p class="text-[8px] font-black text-slate-500 uppercase mb-1">Rating</p>
                                            <p class="text-[10px] font-bold text-white">
                                                {{ $insights['cv']['equipment']['score'] >= 90 ? 'S' : ($insights['cv']['equipment']['score'] >= 75 ? 'A' : ($insights['cv']['equipment']['score'] >= 50 ? 'B' : 'C')) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-6 pt-4 border-t border-white/5 text-center">
                                    <span class="text-[9px] font-black text-slate-600 uppercase tracking-widest">Weight:
                                        20%</span>
                                </div>
                            </div>
                        </div>

                        <!-- CV Footer: Info and Actions -->
                        <div
                            class="flex flex-col md:flex-row justify-between items-center gap-8 pt-10 border-t border-white/5">
                            <div class="flex flex-col items-center md:items-start gap-2">
                                <p class="text-[10px] text-slate-500 font-bold tracking-widest uppercase">Generated by
                                    CoC Deep Insight System v2.0</p>
                                <p class="text-[9px] text-slate-600 italic">Evaluasi strategis war-focused berbasis data
                                    TH{{ $player['townHallLevel'] }} &bull; War-Ready logic active</p>
                            </div>

                            <div class="flex items-center gap-4 no-screenshot">
                                <button onclick="copyToClipboard(this)"
                                    class="group bg-indigo-600 hover:bg-indigo-500 text-white px-8 py-4 rounded-2xl transition-all shadow-xl shadow-indigo-900/20 flex items-center gap-3">
                                    <span class="text-xs font-black uppercase tracking-widest">Copy Profile Link</span>
                                    <svg class="w-4 h-4 group-hover:rotate-12 transition-transform" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                    </svg>
                                </button>
                                <button onclick="toggleScreenshotMode()"
                                    class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-6 py-4 rounded-2xl border border-slate-700 transition-all text-xs font-black uppercase tracking-widest">
                                    Capture View
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <script>
            function copyToClipboard(btn) {
                const url = window.location.href;
                const originalText = btn.querySelector('span').innerText;

                navigator.clipboard.writeText(url).then(() => {
                    btn.querySelector('span').innerText = 'LINK COPIED!';
                    btn.classList.replace('bg-indigo-600', 'bg-green-600');

                    setTimeout(() => {
                        btn.querySelector('span').innerText = originalText;
                        btn.classList.replace('bg-green-600', 'bg-indigo-600');
                    }, 2000);
                });
            }

            function toggleScreenshotMode() {
                const isScreenshot = document.body.classList.toggle('screenshot-mode');
                const indicator = document.getElementById('screenshot-indicator');
                const exitBtn = document.getElementById('exit-screenshot-btn');

                if (isScreenshot) {
                    if (indicator) indicator.classList.remove('hidden');
                    if (exitBtn) exitBtn.classList.remove('hidden');
                    document.getElementById('clan-cv').scrollIntoView({ behavior: 'smooth' });
                } else {
                    if (indicator) indicator.classList.add('hidden');
                    if (exitBtn) exitBtn.classList.add('hidden');
                }
            }
        </script>

        <!-- Floating Exit Button for Screenshot Mode -->
        <button id="exit-screenshot-btn" onclick="toggleScreenshotMode()"
            class="hidden fixed bottom-8 left-1/2 -translate-x-1/2 z-[100] bg-orange-600 text-white px-6 py-3 rounded-full font-black text-xs uppercase tracking-[0.2em] shadow-2xl border border-orange-500/50 hover:bg-orange-500 transition-all no-screenshot-btn">
            Exit Screenshot Mode
        </button>
        <div class="flex flex-col md:flex-row justify-between items-end gap-4 no-screenshot">
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
                        <div class="text-center py-10 bg-slate-900/20 rounded-3xl border border-dashed border-slate-800">
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
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
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