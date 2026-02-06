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
            <!-- Basic Profile (Redesigned for Screenshot-able Look) -->
            <div class="lg:col-span-2 relative overflow-hidden rounded-3xl group">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/40 via-slate-900 to-slate-900 z-0"></div>
                <div
                    class="absolute -top-24 -right-24 w-64 h-64 bg-orange-500/10 rounded-full blur-3xl group-hover:bg-orange-500/20 transition-all duration-700">
                </div>

                <div class="relative z-10 p-6 md:p-10 flex flex-col md:flex-row items-center gap-8 shine">
                    <div class="relative shrink-0">
                        <div
                            class="w-28 h-28 bg-gradient-to-br from-orange-400 to-red-600 rounded-[2rem] flex items-center justify-center text-6xl font-black text-white shadow-[0_0_40px_rgba(234,88,12,0.3)] transform -rotate-3 hover:rotate-0 transition-transform duration-500">
                            {{ $player['townHallLevel'] }}
                        </div>
                        <div
                            class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-white text-slate-900 px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest shadow-xl border-2 border-slate-900">
                            TH {{ $player['townHallLevel'] }}
                        </div>
                    </div>

                    <div class="text-center md:text-left">
                        <div class="flex flex-col md:flex-row md:items-center gap-2 mb-2">
                            <h1
                                class="text-4xl md:text-6xl font-black tracking-tighter text-transparent bg-clip-text bg-gradient-to-r from-white via-slate-200 to-slate-400">
                                {{ $player['name'] }}
                            </h1>
                            @if($insights['health']['score'] >= 90)
                                <div
                                    class="inline-flex items-center gap-1 bg-yellow-400 text-slate-900 px-3 py-1 rounded-full text-[10px] font-black uppercase self-center md:self-auto animate-bounce mt-2 md:mt-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    ELITE PLAYER
                                </div>
                            @endif
                        </div>

                        <p
                            class="text-orange-500 font-mono text-xl font-bold mb-6 tracking-[0.2em] flex items-center justify-center md:justify-start gap-2">
                            <span class="opacity-50">#</span>{{ $player['tag'] }}
                        </p>

                        <div class="flex flex-wrap justify-center md:justify-start gap-3">
                            @if(isset($player['clan']))
                                <div
                                    class="flex items-center gap-3 bg-white/5 backdrop-blur-md px-5 py-2.5 rounded-2xl border border-white/10 shadow-lg">
                                    <div class="w-3 h-3 rounded-full bg-blue-500 shadow-[0_0_10px_rgba(59,130,246,0.5)]">
                                    </div>
                                    <span
                                        class="text-sm font-bold text-slate-200 uppercase tracking-tight">{{ $player['clan']['name'] }}</span>
                                </div>
                            @endif
                            <div
                                class="bg-indigo-500/10 backdrop-blur-md px-5 py-2.5 rounded-2xl border border-indigo-500/20 shadow-lg">
                                <span
                                    class="text-xs font-black text-indigo-400 uppercase tracking-widest">{{ $player['league']['name'] ?? 'NO LEAGUE' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- High Progress Motivational Text -->
                @if($insights['health']['score'] >= 90)
                    <div
                        class="absolute bottom-0 left-0 right-0 bg-gradient-to-r from-yellow-500/20 via-transparent to-yellow-500/20 py-2 border-t border-yellow-500/10">
                        <p
                            class="text-[10px] text-center font-black text-yellow-500 uppercase tracking-[0.3em] animate-pulse">
                            @php
                                $quotes = [
                                    "Master Strategi Sejati! Akun Anda hampir mencapai puncak kejayaan!",
                                    "Luar Biasa! Pertahanan dan Pasukan Anda adalah mimpi buruk lawan!",
                                    "Aura Juara! Tinggal selangkah lagi menuju Max Out sempurna!",
                                    "Inspirasi Clan! Statistik Anda menunjukkan dedikasi level tinggi!"
                                ];
                                echo $quotes[array_rand($quotes)];
                            @endphp
                        </p>
                    </div>
                @endif
            </div>

            <!-- War Readiness Card (Styled as a supporting badge) -->
            <div
                class="glass-card rounded-3xl p-8 flex flex-col justify-between border-t border-white/5 relative overflow-hidden shine">
                <div
                    class="absolute -top-10 -right-10 w-32 h-32 bg-{{ $insights['warReadiness']['status_id'] == 'ready' ? 'green' : ($insights['warReadiness']['status_id'] == 'semi_ready' ? 'amber' : 'red') }}-500/10 blur-3xl">
                </div>

                <div>
                    <div class="flex justify-between items-start mb-4">
                        <p class="text-slate-500 text-[10px] uppercase font-bold tracking-[0.2em]">War Status</p>
                        <div
                            class="w-2 h-2 rounded-full bg-{{ $insights['warReadiness']['status_id'] == 'ready' ? 'green' : ($insights['warReadiness']['status_id'] == 'semi_ready' ? 'amber' : 'red') }}-500 animate-ping">
                        </div>
                    </div>
                    <h3
                        class="text-4xl font-black leading-none mb-2 tracking-tighter {{ $insights['warReadiness']['status_id'] == 'ready' ? 'text-green-500' : ($insights['warReadiness']['status_id'] == 'semi_ready' ? 'text-amber-500' : 'text-red-500') }}">
                        {{ $insights['warReadiness']['label'] }}
                    </h3>
                    <p class="text-[11px] text-slate-400 leading-relaxed font-medium mt-4 line-clamp-3">
                        {{ $insights['warReadiness']['reason'] }}
                    </p>
                </div>

                <div class="mt-8 pt-4 border-t border-slate-800/50">
                    <div class="flex justify-between items-center text-[10px] font-black uppercase text-slate-500">
                        <span>Readiness Level</span>
                        <span
                            class="text-white">{{ $insights['warReadiness']['status_id'] == 'ready' ? '100%' : ($insights['warReadiness']['status_id'] == 'semi_ready' ? '75%' : '40%') }}</span>
                    </div>
                </div>
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
                        {{ $insights['health']['status'] }}
                    </div>
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

        <!-- SUGGESTIONS & FEEDBACK SECTION -->
        <section class="space-y-6 pt-10 border-t border-slate-800/50">
            <div class="flex flex-col md:flex-row justify-between items-end gap-4">
                <div>
                    <h2 class="text-2xl font-black flex items-center gap-3">
                        <span class="w-2 h-6 bg-blue-500 rounded-full"></span>
                        SARAN & MASUKAN
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">Berikan masukan untuk meningkatkan strategi player ini.</p>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-500/10 border border-green-500/20 text-green-500 p-4 rounded-2xl text-xs font-bold animate-pulse">
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
                    <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-widest px-1">Saran Terbaru</h3>
                    <div class="space-y-3 max-h-[400px] overflow-y-auto custom-scrollbar pr-2">
                        @forelse($suggestions as $s)
                            <div class="glass-card p-4 rounded-2xl border-l-2 border-l-blue-500/50">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-xs font-black text-slate-200">{{ $s->name ?? 'Anonim' }}</span>
                                    <span class="text-[9px] text-slate-600 font-mono">{{ $s->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-[11px] text-slate-400 leading-relaxed italic">"{{ $s->suggestion }}"</p>
                            </div>
                        @empty
                            <div class="text-center py-10 bg-slate-900/20 rounded-3xl border border-dashed border-slate-800">
                                <p class="text-xs text-slate-600">Belum ada saran untuk player ini. <br> Jadilah yang pertama memberikan masukan!</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Suggestion Form -->
                <div class="glass-card rounded-3xl p-6 border-t border-white/5">
                    <h3 class="text-sm font-black text-white mb-6">Kirim Saran Baru</h3>
                    <form action="{{ route('suggestions.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="tag_id" value="{{ $player['tag'] }}">
                        
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5 ml-1">Nama (Opsional)</label>
                            <input type="text" name="name" placeholder="Contoh: Master COC" 
                                class="w-full bg-slate-900/50 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-blue-500/50 transition-colors">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5 ml-1">Saran Strategi</label>
                            <textarea name="suggestion" required rows="4" placeholder="Tuliskan saran Anda di sini..."
                                class="w-full bg-slate-900/50 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-slate-200 focus:outline-none focus:border-blue-500/50 transition-colors resize-none"></textarea>
                        </div>

                        <button type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-500 text-white text-[11px] font-black uppercase tracking-widest py-3.5 rounded-xl transition-all shadow-lg shadow-blue-900/20 flex items-center justify-center gap-2">
                            <span>Kirim Saran</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="pt-16 text-center">
            <p class="text-[9px] text-slate-700 uppercase tracking-[0.4em] font-black">CoC Deep Insight System &bull; 2026</p>
        </footer>
    </div>
</body>

</html>