<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $player['name'] }} | Analisis Lanjutan</title>
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
    </style>
</head>

<body class="text-slate-200 min-h-screen pb-12">
    <div class="max-w-4xl mx-auto px-4 py-6 space-y-6">

        <!-- Top Nav & Source Info -->
        <div class="flex items-center justify-between mb-2">
            <a href="{{ route('player.home') }}"
                class="group flex items-center gap-2 text-slate-400 hover:text-white transition-all text-sm">
                <span class="bg-slate-800 p-1.5 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </span>
                Mulai Baru
            </a>
            <div class="text-[10px] text-slate-500 uppercase flex items-center gap-3">
                <span class="font-bold tracking-tighter">{{ $source }} Cache</span>
                <span class="w-px h-2.5 bg-slate-700"></span>
                <span>{{ \Carbon\Carbon::parse($lastFetchedAt)->diffForHumans() }}</span>
            </div>
        </div>

        <!-- Section 1: HEADER (INFO UTAMA) -->
        <div
            class="glass-card rounded-2xl p-5 md:p-6 flex flex-col md:flex-row items-center md:items-start gap-5 border-l-4 border-l-amber-500 shadow-xl">
            <div class="relative shrink-0">
                <div
                    class="w-20 h-20 bg-gradient-to-br from-amber-400 to-orange-600 rounded-2xl flex items-center justify-center text-3xl font-black text-white shadow-lg">
                    {{ $player['townHallLevel'] }}
                </div>
                <div
                    class="absolute -bottom-1.5 left-1/2 -translate-x-1/2 bg-slate-900 border border-slate-700 px-2 py-0.5 rounded-full text-[8px] font-black uppercase whitespace-nowrap">
                    TH LEVEL</div>
            </div>
            <div class="text-center md:text-left flex-grow">
                <h1 class="text-2xl md:text-3xl font-black tracking-tight mb-0.5">{{ $player['name'] }}</h1>
                <p class="text-orange-500 font-mono text-sm font-bold mb-3 tracking-wider">{{ $player['tag'] }}</p>
                <div class="flex flex-wrap justify-center md:justify-start gap-1.5">
                    @if(isset($player['clan']))
                        <span
                            class="bg-blue-500/10 text-blue-400 px-2.5 py-1 rounded-lg border border-blue-500/20 text-[10px] font-bold uppercase">{{ $player['clan']['name'] }}</span>
                    @endif
                    <span
                        class="bg-slate-700/50 text-slate-300 px-2.5 py-1 rounded-lg border border-slate-600 text-[10px] font-bold uppercase">{{ $player['league']['name'] ?? 'Unranked' }}</span>
                </div>
            </div>
            <div class="hidden md:block w-px h-12 bg-slate-700/50 self-center"></div>
            <div class="text-center shrink-0">
                <p class="text-[9px] text-slate-500 uppercase tracking-widest mb-0.5 font-bold">Status War</p>
                <div
                    class="text-lg font-black {{ $insights['warReadiness']['isReady'] ? 'text-green-500' : 'text-red-500' }}">
                    {{ strtoupper($insights['warReadiness']['status']) }}
                </div>
            </div>
        </div>

        <!-- Section 2: SUMMARY METRICS (PENTING) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Health Score -->
            <div class="glass-card rounded-2xl p-5 flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-[10px] uppercase font-bold tracking-widest mb-1">Kesehatan Akun</p>
                    <h2 class="text-4xl font-black text-white leading-tight">{{ $insights['health']['score'] }}%</h2>
                    <p
                        class="text-[11px] font-bold mt-1 {{ $insights['health']['score'] >= 85 ? 'text-green-400' : ($insights['health']['score'] >= 65 ? 'text-amber-400' : 'text-red-400') }}">
                        {{ strtoupper($insights['health']['status']) }}
                    </p>
                </div>
                <div
                    class="w-14 h-14 bg-slate-800/50 rounded-full flex items-center justify-center border-4 border-slate-700 shadow-inner">
                    <span class="text-amber-500 text-xs font-black">TOP</span>
                </div>
            </div>

            <!-- Rush Status -->
            <div
                class="glass-card rounded-2xl p-5 flex flex-col justify-center border-b-2 {{ $insights['rush']['isRushed'] ? 'border-b-red-500' : 'border-b-green-500' }}">
                <p class="text-slate-500 text-[10px] uppercase font-bold tracking-widest mb-1">Status Pengembangan</p>
                <div
                    class="text-2xl font-black {{ $insights['rush']['isRushed'] ? 'text-red-500' : 'text-green-500' }}">
                    {{ strtoupper($insights['rush']['status']) }}
                </div>
                @if($insights['rush']['reasons'])
                    <p class="text-[10px] text-slate-500 mt-1 italic">{{ $insights['rush']['reasons'][0] }}</p>
                @endif
            </div>
        </div>

        <!-- Section 3: HEROES (PRIORITAS) -->
        <section class="glass-card rounded-2xl p-5 md:p-6 space-y-5">
            <h2 class="text-lg font-bold flex items-center gap-2">
                <span class="w-1.5 h-5 bg-orange-500 rounded-full"></span>
                Kesiapan Hero
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($insights['heroes']['list'] as $hero)
                    <div class="bg-slate-900/40 p-3.5 rounded-xl border border-slate-800">
                        <div class="flex justify-between items-center mb-2.5">
                            <span class="text-xs font-bold">{{ $hero['name'] }}</span>
                            <span class="text-[10px] font-mono text-slate-400">Lv {{ $hero['level'] }} /
                                {{ $hero['maxLevel'] }}</span>
                        </div>
                        @php $prog = ($hero['level'] / $hero['maxLevel']) * 100; @endphp
                        <div class="w-full h-1.5 bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-orange-600 to-amber-400 transition-all duration-700"
                                style="width: {{ $prog }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($insights['heroOrder'])
                <div class="mt-4 pt-4 border-t border-slate-700/30">
                    <h3 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3">Prioritas Upgrade Hero
                        Berikutnya:</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach(collect($insights['heroOrder'])->take(3) as $h)
                            <span
                                class="bg-amber-500/10 text-amber-500 text-[9px] px-2 py-1 rounded-md border border-amber-500/20 font-bold">1.
                                {{ $h['name'] }}</span>
                            @break
                        @endforeach
                    </div>
                </div>
            @endif
        </section>

        <!-- Section 4: REKOMENDASI UPGRADE -->
        <section class="space-y-4">
            <h2 class="text-lg font-bold flex items-center gap-2 px-1">
                <span class="w-1.5 h-5 bg-blue-500 rounded-full"></span>
                Saran Upgrade
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($recommendations as $rec)
                    <div
                        class="glass-card p-4 rounded-xl border-l-2 {{ $rec['priority'] == 'Tinggi' ? 'border-l-red-500' : 'border-l-blue-500' }}">
                        <div class="flex justify-between items-start mb-1">
                            <h4 class="text-xs font-bold text-slate-200 uppercase">{{ $rec['title'] }}</h4>
                            <span
                                class="text-[8px] font-black px-1.5 py-0.5 rounded-md bg-slate-800 text-slate-400 border border-slate-700 tracking-tighter">{{ $rec['priority'] }}</span>
                        </div>
                        <p class="text-[10px] text-slate-500 leading-relaxed">{{ $rec['reason'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Section 5: BULK DATA (DITAMPUNG DI BAWAH) -->
        <section class="space-y-6 pt-6 border-t border-slate-800">
            <h2 class="text-sm font-bold text-slate-500 uppercase tracking-widest text-center">Data Detail & Koleksi
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Troops List -->
                <div class="glass-card rounded-2xl p-5 flex flex-col h-[400px]">
                    <h3 class="text-xs font-bold text-slate-400 mb-4 uppercase">Koleksi Pasukan</h3>
                    <div class="flex-grow overflow-y-auto custom-scrollbar space-y-2.5 pr-1">
                        @foreach($insights['troops']['list'] as $troop)
                            <div
                                class="flex items-center justify-between bg-slate-900/30 p-2.5 rounded-lg border border-slate-800/50">
                                <div class="flex-grow">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-[10px] font-medium">{{ $troop['name'] }}</span>
                                        <span
                                            class="text-[9px] font-mono {{ $troop['status'] == 'MAX' ? 'text-green-500' : 'text-slate-500' }}">Lv
                                            {{ $troop['level'] }} / {{ $troop['maxLevel'] }}</span>
                                    </div>
                                    <div class="w-full h-1 bg-slate-800 rounded-full overflow-hidden">
                                        <div class="h-full {{ $troop['status'] == 'MAX' ? 'bg-green-500' : 'bg-slate-600' }}"
                                            style="width: {{ $troop['progress'] }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Spells & Equipment (Vertical Stack) -->
                <div class="space-y-6">
                    <!-- Spells List -->
                    <div class="glass-card rounded-2xl p-5 flex flex-col h-[220px]">
                        <h3 class="text-xs font-bold text-slate-400 mb-4 uppercase">Koleksi Mantra</h3>
                        <div class="flex-grow overflow-y-auto custom-scrollbar space-y-2 pr-1">
                            @foreach($insights['spells']['list'] as $spell)
                                <div
                                    class="flex justify-between items-center border-b border-slate-800/50 pb-2 mb-2 last:border-0">
                                    <span class="text-[10px]">{{ $spell['name'] }}</span>
                                    <span class="text-[10px] font-mono text-blue-400 font-bold">Lv.
                                        {{ $spell['level'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Equipment List -->
                    <div class="glass-card rounded-2xl p-5 flex flex-col h-[220px]">
                        <h3 class="text-xs font-bold text-slate-400 mb-4 uppercase">Peralatan Hero</h3>
                        <div class="flex-grow overflow-y-auto custom-scrollbar space-y-2 pr-1">
                            @foreach($insights['equipment']['list'] as $item)
                                <div class="flex justify-between items-center p-2 bg-slate-900/30 rounded-lg">
                                    <span class="text-[10px]">{{ $item['name'] }}</span>
                                    <span
                                        class="text-[10px] font-black {{ $item['level'] == $item['maxLevel'] ? 'text-green-500' : 'text-purple-400' }}">Lv.
                                        {{ $item['level'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Clan Info (Bottom) -->
            <div class="glass-card rounded-2xl p-5 flex flex-wrap gap-8 justify-between items-center opacity-70">
                <div class="flex flex-col">
                    <span class="text-[9px] font-bold text-slate-500 uppercase mb-1 tracking-wider">Kontribusi
                        Klan</span>
                    <span class="text-xs font-bold text-slate-300">{{ $insights['clan']['donations'] }} Donasi /
                        {{ $insights['clan']['capital'] }} Kapital</span>
                </div>
                <div class="flex flex-col text-right">
                    <span class="text-[9px] font-bold text-slate-500 uppercase mb-1 tracking-wider">Peran</span>
                    <span class="text-xs font-bold text-slate-300">{{ $insights['clan']['role'] }}</span>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="pt-12 text-center opacity-30 px-6">
            <p class="text-[8px] leading-relaxed max-w-lg mx-auto">
                Analisis ditenagai oleh API Clash of Clans resmi. Konten ini tidak memiliki hubungan resmi dengan
                Supercell.
            </p>
            <p class="text-[8px] mt-2 brightness-50 uppercase tracking-[0.2em]">&copy; 2026 Developer System</p>
        </footer>
    </div>
</body>

</html>