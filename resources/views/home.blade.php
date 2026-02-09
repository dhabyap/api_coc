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
        
        html {
            scroll-behavior: smooth;
            scroll-padding-top: 6rem; /* Offset for fixed navbar */
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
    <!-- Navbar -->
    <nav
        class="fixed top-0 left-0 w-full z-50 transition-all duration-300 glass border-b border-white/5 bg-[#0b0e14]/80 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('player.home') }}" class="text-2xl font-black tracking-tighter text-white">
                    COC <span class="text-orange-500">ANALYST</span>
                </a>
                <div class="hidden md:flex items-center gap-6 ml-8">
                    <a href="{{ route('player.home') }}"
                        class="text-sm font-bold text-white/50 hover:text-orange-500 transition-colors">HOME</a>
                    <a href="#events-section"
                        class="text-sm font-bold text-white/50 hover:text-orange-500 transition-colors">EVENTS</a>
                    <a href="#stats-section"
                        class="text-sm font-bold text-white/50 hover:text-orange-500 transition-colors">INSIGHTS</a>
                    <a href="#contact-section"
                        class="text-sm font-bold text-white/50 hover:text-orange-500 transition-colors">CONTACT</a>
                </div>
            </div>
            <a href="#search-form"
                class="hidden md:inline-flex items-center justify-center px-5 py-2 rounded-full bg-orange-500/10 border border-orange-500/20 text-orange-500 text-xs font-bold uppercase tracking-wider hover:bg-orange-500 hover:text-white transition-all">
                Analisis Sekarang
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative pt-40 pb-20 px-6 overflow-hidden">
        <div
            class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[600px] bg-gradient-to-b from-orange-500/10 via-orange-500/5 to-transparent -z-10 blur-3xl">
        </div>

        <div class="max-w-4xl mx-auto text-center relative z-10">
            <div
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 mb-6 backdrop-blur-sm">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">System Online v3.0</span>
            </div>

            <h1 class="text-5xl md:text-7xl font-black mb-6 tracking-tight leading-tight text-white drop-shadow-2xl">
                Clash of Clans<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-red-500">Analisis &
                    Rekomendasi</span>
            </h1>

            <p class="text-slate-400 text-lg md:text-xl mb-12 max-w-2xl mx-auto leading-relaxed">
                Platform penentu <strong>Prioritas Upgrade</strong> untuk War. Berbasis kecerdasan buatan dan kondisi
                nyata akun Anda.
            </p>

            <div class="max-w-lg mx-auto p-2 rounded-2xl glass mb-8 shadow-2xl relative group">
                <div
                    class="absolute -inset-1 bg-gradient-to-r from-orange-500/20 to-purple-500/20 rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-1000">
                </div>
                <form id="search-form" action="{{ route('player.search') }}" method="GET"
                    class="relative flex flex-col md:flex-row gap-2">
                    <div class="relative flex-grow">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-500 font-bold">#</span>
                        <input id="tag-input" type="text" name="tag" placeholder="MASUKKAN TAG PLAYER"
                            class="w-full bg-[#0b0e14]/80 border border-white/10 rounded-xl py-4 pl-9 pr-4 outline-none focus:ring-2 focus:ring-orange-500/50 transition-all uppercase placeholder:text-slate-600 font-bold text-white">
                    </div>
                    <button type="submit"
                        class="bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 text-white font-black py-4 px-8 rounded-xl shadow-lg transition-all active:scale-95 whitespace-nowrap tracking-wide">
                        ANALISIS
                    </button>
                </form>
            </div>

            <!-- Recent Searches Section -->
            <div id="recent-searches-container" class="hidden max-w-lg mx-auto">
                <p class="text-[10px] font-black text-slate-600 uppercase tracking-[0.2em] mb-4">Terakhir Dicari</p>
                <div id="recent-tags-list" class="flex flex-wrap justify-center gap-2">
                    <!-- Pills injected via JS -->
                </div>
            </div>

            @if(session('error'))
                <div class="mt-8">
                    <p
                        class="text-red-400 text-sm font-bold bg-red-500/10 inline-block px-6 py-2 rounded-full border border-red-500/20">
                        {{ session('error') }}
                    </p>
                </div>
            @endif
        </div>
    </header>

    <!-- Recurring In-Game Events Section -->
    <section id="events-section" class="max-w-6xl mx-auto px-6 mb-24 relative z-10">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-xs font-bold text-orange-500 uppercase tracking-[0.2em]">Live Events</h2>
            <div class="h-px bg-white/10 flex-grow ml-6"></div>
        </div>

        <div id="events-container" class="grid grid-cols-2 md:grid-cols-5 gap-4">
            @foreach($events as $event)
                <div
                    class="stat-card p-5 rounded-2xl border border-white/5 text-center group hover:border-orange-500/30 transition-all duration-300 relative overflow-hidden">
                    <div
                        class="absolute inset-0 bg-gradient-to-b from-white/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                    </div>
                    <div class="relative z-10">
                        <div class="mb-3">
                            @if($event['status'] === 'ACTIVE')
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-green-500/10 border border-green-500/20 text-green-400 text-[10px] font-black uppercase tracking-wider">
                                    <span class="w-1 h-1 rounded-full bg-green-500 animate-pulse"></span> Active
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-blue-500/10 border border-blue-500/20 text-blue-400 text-[10px] font-black uppercase tracking-wider">
                                    Upcoming
                                </span>
                            @endif
                        </div>
                        <h3 class="text-sm font-bold text-white mb-1.5">{{ $event['name'] }}</h3>
                        <p class="text-xs text-slate-400 font-mono font-bold" data-event-key="{{ $event['key'] }}">
                            {{ $event['countdown'] }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        <div id="events-error" class="hidden text-center text-slate-600 text-xs py-4">
            Event data unavailable
        </div>
    </section>

    <!-- Global Stats -->
    <!-- Global Stats -->
    <section id="stats-section" class="max-w-6xl mx-auto px-6 mb-24">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="stat-card p-8 rounded-3xl border border-white/5 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg class="w-16 h-16 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <p class="text-orange-500 text-[10px] uppercase font-black tracking-widest mb-2">Total Analisis</p>
                <h3 class="text-4xl md:text-5xl font-black text-white tracking-tight">
                    {{ number_format($globalStats['totalAccounts']) }}</h3>
                <p class="text-slate-500 text-xs mt-2">Akun telah dianalisis sistem</p>
            </div>
            <div class="stat-card p-8 rounded-3xl border border-white/5 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg class="w-16 h-16 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-green-500 text-[10px] uppercase font-black tracking-widest mb-2">Ready War</p>
                <h3 class="text-4xl md:text-5xl font-black text-white tracking-tight">
                    {{ number_format($globalStats['warReadyCount']) }}</h3>
                <p class="text-slate-500 text-xs mt-2">Akun siap tempur terdeteksi</p>
            </div>
            <div class="stat-card p-8 rounded-3xl border border-white/5 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg class="w-16 h-16 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <p class="text-blue-500 text-[10px] uppercase font-black tracking-widest mb-2">Top Recommendation</p>
                <h3 class="text-3xl md:text-4xl font-black text-white tracking-tight truncate">
                    {{ $globalStats['topRecommendedTroop'] }}</h3>
                <p class="text-slate-500 text-xs mt-2">Pasukan paling disarankan</p>
            </div>
        </div>
    </section>

    <!-- Recent Activity Section (SI LAB RANDOM) -->
    <!-- Recent Activity Section -->
    <section class="max-w-6xl mx-auto px-6 mb-24">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-xs font-bold text-slate-500 uppercase tracking-[0.2em]">Aktivitas Analisis Terbaru</h2>
            <div class="flex items-center gap-2 px-3 py-1 rounded-full bg-green-500/10 border border-green-500/20">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                <span class="text-[10px] text-green-500 font-bold uppercase tracking-wider">Live Data</span>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($globalStats['recentAnalyses'] as $analysis)
                <div class="player-card p-5 rounded-2xl bg-white/[0.02] border border-white/5 hover:bg-white/[0.04] transition-all group">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 bg-slate-800/50 rounded-xl flex items-center justify-center text-lg font-black text-white border border-white/5 group-hover:border-orange-500/30 group-hover:text-orange-500 transition-colors">
                            {{ $analysis['th'] }}
                        </div>
                        <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider">{{ $analysis['time'] }}</span>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-200 truncate mb-1">
                            {{ $analysis['name'] }}
                        </h4>
                        <p class="text-[10px] text-slate-500 font-mono">{{ $analysis['tag'] ?? 'PLAYER' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Suggestions Section -->
    <section id="contact-section" class="max-w-4xl mx-auto px-6 mb-32 relative z-10">
        <div class="glass p-8 md:p-12 rounded-3xl border border-white/10 shadow-2xl relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-12 opacity-5 group-hover:opacity-10 transition-opacity duration-500">
                <svg class="w-32 h-32 text-orange-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-7 12h-2v-2h2v2zm0-4h-2V6h2v4z" />
                </svg>
            </div>

            <div class="relative z-10">
                <div class="mb-8">
                    <h2 class="text-3xl font-black mb-3 text-white">Hubungi Developer</h2>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-xl">
                        Punya ide fitur baru atau menemukan bug? Beritahu pengembang untuk membuat platform ini jadi lebih baik!
                    </p>
                </div>

                @if(session('success'))
                    <div class="bg-green-500/10 border border-green-500/20 text-green-500 px-6 py-4 rounded-xl mb-8 text-sm font-bold flex items-center gap-3">
                        <span class="text-xl">✅</span>
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('suggestions.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2 ml-1">Tag CoC Anda (Wajib)</label>
                            <div class="relative group/input">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-500 font-bold text-sm group-focus-within/input:text-orange-500 transition-colors">#</span>
                                <input type="text" name="tag_id" required placeholder="P8Y28RRLL"
                                    class="w-full bg-[#0b0e14]/50 border border-white/10 rounded-xl py-4 pl-9 pr-4 outline-none focus:ring-2 focus:ring-orange-500/50 transition-all text-sm uppercase font-bold font-mono placeholder:text-slate-700 text-white">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2 ml-1">Nama (Opsional)</label>
                            <input type="text" name="name" placeholder="Siapa nama Anda?"
                                class="w-full bg-[#0b0e14]/50 border border-white/10 rounded-xl py-4 px-4 outline-none focus:ring-2 focus:ring-orange-500/50 transition-all text-sm font-bold text-white placeholder:text-slate-700">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2 ml-1">Pesan / Saran</label>
                        <textarea name="suggestion" rows="4" required
                            placeholder="Tuliskan ide fitur baru, laporan bug, atau saran lainnya..."
                            class="w-full bg-[#0b0e14]/50 border border-white/10 rounded-xl py-4 px-4 outline-none focus:ring-2 focus:ring-orange-500/50 transition-all text-sm font-medium text-white placeholder:text-slate-700 resize-none"></textarea>
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-black py-4 rounded-xl shadow-lg transition-all active:scale-[0.98] uppercase tracking-wider text-sm">
                        Kirim Masukan Ke Developer
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-24 px-6 border-t border-white/5 bg-gradient-to-b from-[#0b0e14] to-[#0f1218]">
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-12">
            <div class="space-y-4">
                <h3 class="text-xl font-black text-white flex items-center gap-3">
                    <span class="text-orange-500 text-sm bg-orange-500/10 px-2 py-1 rounded inline-block">01</span>
                    Rekomendasi Dinamis
                </h3>
                <p class="text-slate-500 text-sm leading-relaxed">Menghitung prioritas upgrade paling efektif berdasarkan Town Hall dan kesiapan Hero saat ini.</p>
            </div>
            <div class="space-y-4">
                <h3 class="text-xl font-black text-white flex items-center gap-3">
                    <span class="text-blue-500 text-sm bg-blue-500/10 px-2 py-1 rounded inline-block">02</span>
                    Kesiapan Perang
                </h3>
                <p class="text-slate-500 text-sm leading-relaxed">Algoritma khusus untuk mendeteksi apakah akun Anda berkategori Siap, Semi-Siap, atau Prematur.</p>
            </div>
            <div class="space-y-4">
                <h3 class="text-xl font-black text-white flex items-center gap-3">
                    <span class="text-purple-500 text-sm bg-purple-500/10 px-2 py-1 rounded inline-block">03</span>
                    Strategi Meta
                </h3>
                <p class="text-slate-500 text-sm leading-relaxed">Saran set peralatan hero (Gear) dan pasukan super yang paling relevan dengan gaya main Anda.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 text-center px-6 border-t border-white/5 bg-[#0b0e14]">
        <p class="text-slate-700 text-[10px] font-black uppercase tracking-[0.4em]">
            &copy; {{ date('Y') }} COC STRATEGY ENGINE v3.0
        </p>
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

            // Smooth Scroll for Anchor Links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;
                    
                    const target = document.querySelector(targetId);
                    if (target) {
                        e.preventDefault();
                        const headerOffset = 96; // 6rem ~ 96px
                        const elementPosition = target.getBoundingClientRect().top;
                        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
        
                        window.scrollTo({
                            top: offsetPosition,
                            behavior: "smooth"
                        });
                    }
                });
            });
        });

        // Event Auto-Update System
        (function () {
            const eventsContainer = document.getElementById('events-container');
            const eventsError = document.getElementById('events-error');
            let updateInterval;

            function updateEventCountdowns() {
                fetch('{{ route("events.summary") }}')
                    .then(response => {
                        if (!response.ok) throw new Error('Failed to fetch events');
                        return response.json();
                    })
                    .then(data => {
                        if (data.events && data.events.length > 0) {
                            data.events.forEach(event => {
                                const countdownEl = document.querySelector(`[data-event-key="${event.key}"]`);
                                if (countdownEl) {
                                    countdownEl.textContent = event.countdown;
                                }
                            });

                            // Hide error, show container
                            eventsError.classList.add('hidden');
                            eventsContainer.classList.remove('hidden');
                        }
                    })
                    .catch(error => {
                        console.error('Event update failed:', error);
                        // Show error only if container is empty
                        if (!eventsContainer.children.length) {
                            eventsContainer.classList.add('hidden');
                            eventsError.classList.remove('hidden');
                        }
                    });
            }

            // Update every 60 seconds
            updateInterval = setInterval(updateEventCountdowns, 60000);

            // Cleanup on page unload
            window.addEventListener('beforeunload', () => {
                if (updateInterval) clearInterval(updateInterval);
            });
        })();
    </script>
</body>

</html>