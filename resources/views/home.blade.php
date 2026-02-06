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
                <form action="{{ route('player.search') }}" method="GET" class="flex flex-col md:flex-row gap-2">
                    <div class="relative flex-grow">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 font-bold">#</span>
                        <input type="text" name="tag" placeholder="MASUKKAN TAG"
                            class="w-full bg-gray-800/50 border border-gray-700/50 rounded-xl py-4 pl-8 pr-4 outline-none focus:ring-2 focus:ring-orange-500 transition-all uppercase placeholder:text-gray-600 font-bold">
                    </div>
                    <button type="submit"
                        class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg transition-all active:scale-95 whitespace-nowrap">
                        Analisis Sekarang
                    </button>
                </form>
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
</body>

</html>