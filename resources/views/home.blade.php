<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analisis Player CoC | Beranda</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #0b0e14;
        }

        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .stat-card {
            background: linear-gradient(145deg, rgba(30, 41, 59, 0.4), rgba(15, 23, 42, 0.4));
        }

        .player-card {
            background: rgba(255, 255, 255, 0.02);
            transition: all 0.3s;
            border: 1px solid rgba(255, 255, 255, 0.03);
        }

        .player-card:hover {
            background: rgba(255, 255, 255, 0.05);
            transform: translateY(-2px);
            border-color: rgba(249, 115, 22, 0.2);
        }
    </style>
</head>

<body class="text-gray-100 min-h-screen">
    <!-- Hero Section -->
    <header class="relative py-12 px-6 overflow-hidden">
        <div
            class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[500px] bg-gradient-to-b from-orange-500/10 to-transparent -z-10">
        </div>

        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl md:text-6xl font-black mb-6 tracking-tight leading-tight">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-orange-500">
                    Clash of Clans
                </span><br>
                Analisis & Rekomendasi
            </h1>
            <p class="text-gray-400 text-lg md:text-xl mb-10 max-w-2xl mx-auto leading-relaxed">
                Platform penentu <strong>Prioritas Upgrade</strong> untuk War. Berbasis kecerdasan buatan dan kondisi
                nyata akun Anda.
            </p>

            <div class="max-w-lg mx-auto p-2 rounded-2xl glass mb-6 shadow-2xl">
                <form id="search-form" action="{{ route('player.search') }}" method="GET"
                    class="flex flex-col md:flex-row gap-2">
                    <div class="relative flex-grow">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 font-bold">#</span>
                        <input id="tag-input" type="text" name="tag" placeholder="MASUKKAN TAG"
                            class="w-full bg-gray-800/50 border border-gray-700/50 rounded-xl py-4 pl-8 pr-4 outline-none focus:ring-2 focus:ring-orange-500 transition-all uppercase placeholder:text-gray-600 font-bold">
                    </div>
                    <button type="submit"
                        class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg transition-all active:scale-95 whitespace-nowrap">
                        Analisis Sekarang
                    </button>
                </form>
            </div>

            <!-- Recent Searches Section -->
            <div id="recent-searches-container" class="hidden max-w-lg mx-auto mb-10">
                <p class="text-[10px] font-black text-slate-600 uppercase tracking-[0.2em] mb-3">Terakhir Dicari</p>
                <div id="recent-tags-list" class="flex flex-wrap justify-center gap-2">
                    <!-- Pills injected via JS -->
                </div>
            </div>

            @if(session('error'))
                <p
                    class="text-red-500 text-sm font-medium bg-red-500/10 inline-block px-4 py-1 rounded-full border border-red-500/20 mb-4">
                    {{ session('error') }}
                </p>
            @endif
        </div>
    </header>

    <!-- Global Stats -->
    <section class="max-w-5xl mx-auto px-6 mb-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="stat-card p-6 rounded-2xl border border-white/5">
                <p class="text-slate-500 text-[9px] uppercase font-black tracking-widest mb-1">Total Analisis</p>
                <h3 class="text-3xl font-black text-orange-500">{{ number_format($globalStats['totalAccounts']) }}</h3>
            </div>
            <div class="stat-card p-6 rounded-2xl border border-white/5">
                <p class="text-slate-500 text-[9px] uppercase font-black tracking-widest mb-1">Ready War</p>
                <h3 class="text-3xl font-black text-green-500">{{ number_format($globalStats['warReadyCount']) }}</h3>
            </div>
            <div class="stat-card p-6 rounded-2xl border border-white/5">
                <p class="text-slate-500 text-[9px] uppercase font-black tracking-widest mb-1">Paling Disarankan</p>
                <h3 class="text-xl font-black text-blue-400 truncate">{{ $globalStats['topRecommendedTroop'] }}</h3>
            </div>
        </div>
    </section>

    <!-- Recent Activity Section (SI LAB RANDOM) -->
    <section class="max-w-5xl mx-auto px-6 mb-20">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-sm font-black text-slate-500 uppercase tracking-[0.3em]">Aktivitas Analisis Terbaru</h2>
            <div class="flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                <span class="text-[10px] text-green-500 font-bold uppercase">Live Data</span>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($globalStats['recentAnalyses'] as $analysis)
                <div class="player-card p-5 rounded-2xl">
                    <div class="flex items-start justify-between mb-3">
                        <div
                            class="w-10 h-10 bg-slate-800 rounded-xl flex items-center justify-center text-lg font-black text-white">
                            {{ $analysis['th'] }}
                        </div>
                        <span class="text-[8px] text-slate-600 font-bold uppercase">{{ $analysis['time'] }}</span>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-300 truncate mb-0.5">
                            {{ $analysis['name'] }}
                        </h4>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Suggestions Section -->
    <section class="max-w-3xl mx-auto px-6 mb-24">
        <div class="glass p-8 rounded-3xl border border-white/10 shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 right-0 p-8 opacity-5">
                <svg class="w-24 h-24 text-orange-500" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-7 12h-2v-2h2v2zm0-4h-2V6h2v4z" />
                </svg>
            </div>

            <div class="relative z-10">
                <h2 class="text-2xl font-black mb-2 text-white">Hubungi Developer</h2>
                <p class="text-gray-400 text-sm mb-8 leading-relaxed">Punya ide fitur baru atau menemukan bug? Beritahu
                    pengembang untuk membuat platform ini jadi lebih baik!</p>

                @if(session('success'))
                    <div
                        class="bg-green-500/10 border border-green-500/20 text-green-500 px-4 py-3 rounded-xl mb-6 text-sm font-bold">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('suggestions.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-1.5 ml-1">Tag
                                CoC Anda (Wajib)</label>
                            <div class="relative">
                                <span
                                    class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-600 font-bold text-xs">#</span>
                                <input type="text" name="tag_id" required placeholder="P8Y28RRLL"
                                    class="w-full bg-slate-900/50 border border-white/5 rounded-xl py-3 pl-7 pr-4 outline-none focus:ring-2 focus:ring-orange-500 transition-all text-xs uppercase font-mono">
                            </div>
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-1.5 ml-1">Nama
                                (Opsional)</label>
                            <input type="text" name="name" placeholder="Siapa nama Anda?"
                                class="w-full bg-slate-900/50 border border-white/5 rounded-xl py-3 px-4 outline-none focus:ring-2 focus:ring-orange-500 transition-all text-xs">
                        </div>
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-1.5 ml-1">Pesan
                            / Saran</label>
                        <textarea name="suggestion" rows="4" required
                            placeholder="Tuliskan ide fitur baru, laporan bug, atau saran lainnya..."
                            class="w-full bg-slate-900/50 border border-white/5 rounded-xl py-3 px-4 outline-none focus:ring-2 focus:ring-orange-500 transition-all text-xs resize-none"></textarea>
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-black py-4 rounded-xl shadow-lg transition-all active:scale-[0.98] uppercase tracking-wider text-xs">
                        Kirim Masukan Ke Developer
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 px-6 bg-gray-900/10 border-y border-white/5">
        <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="space-y-4">
                <h3 class="text-lg font-bold text-orange-500">01. Rekomendasi Dinamis</h3>
                <p class="text-gray-500 text-xs leading-relaxed">Menghitung prioritas upgrade paling efektif berdasarkan
                    TH dan kesiapan Hero saat ini.</p>
            </div>
            <div class="space-y-4">
                <h3 class="text-lg font-bold text-blue-500">02. Kesiapan Perang</h3>
                <p class="text-gray-500 text-xs leading-relaxed">Algoritma khusus untuk mendeteksi apakah akun Anda
                    berkategori Siap, Semi-Siap, atau Prematur.</p>
            </div>
            <div class="space-y-4">
                <h3 class="text-lg font-bold text-purple-500">03. Strategi Meta</h3>
                <p class="text-gray-500 text-xs leading-relaxed">Saran set peralatan hero (Gear) dan pasukan super yang
                    paling relevan dengan gaya main Anda.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 text-center px-6 opacity-30">
        <p class="text-gray-600 text-[8px] font-bold uppercase tracking-[0.4em]">&copy; {{ date('Y') }} COC STRATEGY
            ENGINE v3.0</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('search-form');
            const input = document.getElementById('tag-input');
            const container = document.getElementById('recent-searches-container');
            const list = document.getElementById('recent-tags-list');

            function loadRecentTags() {
                const tags = JSON.parse(localStorage.getItem('recent_tags') || '[]');
                if (tags.length > 0) {
                    container.classList.remove('hidden');
                    list.innerHTML = '';
                    tags.forEach(tag => {
                        const pill = document.createElement('button');
                        pill.className = 'glass px-3 py-1.5 rounded-lg text-[10px] font-bold text-slate-400 hover:text-orange-500 hover:border-orange-500/30 transition-all uppercase';
                        pill.textContent = '#' + tag;
                        pill.onclick = () => {
                            input.value = tag;
                            form.submit();
                        };
                        list.appendChild(pill);
                    });
                }
            }

            form.onsubmit = function () {
                const tag = input.value.trim().toUpperCase().replace('#', '');
                if (tag) {
                    let tags = JSON.parse(localStorage.getItem('recent_tags') || '[]');
                    tags = [tag, ...tags.filter(t => t !== tag)].slice(0, 5);
                    localStorage.setItem('recent_tags', JSON.stringify(tags));
                }
            };

            loadRecentTags();
        });
    </script>
</body>

</html>