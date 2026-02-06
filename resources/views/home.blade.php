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
    </style>
</head>

<body class="text-gray-100 min-h-screen">
    <!-- Hero Section -->
    <header class="relative py-16 px-6 overflow-hidden">
        <div
            class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[500px] bg-gradient-to-b from-orange-500/10 to-transparent -z-10">
        </div>

        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl md:text-6xl font-black mb-6 tracking-tight">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-orange-500">
                    Clash of Clans
                </span><br>
                Rekomendasi Upgrade
            </h1>
            <p class="text-gray-400 text-lg md:text-xl mb-10 max-w-2xl mx-auto leading-relaxed">
                Dapatkan prioritas upgrade dinamis untuk <strong>War</strong> berdasarkan kondisi akun Anda.
            </p>

            <div class="max-w-lg mx-auto p-2 rounded-2xl glass mb-6 shadow-2xl">
                <form action="{{ route('player.search') }}" method="GET" class="flex flex-col md:flex-row gap-2">
                    <div class="relative flex-grow">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 font-bold">#</span>
                        <input type="text" name="tag" placeholder="TAG PLAYER"
                            class="w-full bg-gray-800/50 border border-gray-700/50 rounded-xl py-4 pl-8 pr-4 outline-none focus:ring-2 focus:ring-orange-500 transition-all uppercase placeholder:text-gray-600">
                    </div>
                    <button type="submit"
                        class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg transition-all active:scale-95 whitespace-nowrap">
                        Mulai Analisis
                    </button>
                </form>
            </div>

            @if(session('error'))
                <p
                    class="text-red-500 text-sm font-medium bg-red-500/10 inline-block px-4 py-1 rounded-full border border-red-500/20">
                    {{ session('error') }}</p>
            @endif
        </div>
    </header>

    <!-- Global Stats Section (HIDUP) -->
    <section class="max-w-5xl mx-auto px-6 mb-20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="stat-card p-6 rounded-2xl border border-white/5 text-center">
                <p class="text-slate-500 text-[10px] uppercase font-bold tracking-widest mb-1">Total Akun Teranalisis
                </p>
                <h3 class="text-3xl font-black text-orange-500">{{ number_format($globalStats['totalAccounts']) }}</h3>
                <p class="text-[9px] text-slate-600 mt-2 italic">Data anonim platform</p>
            </div>
            <div class="stat-card p-6 rounded-2xl border border-white/5 text-center">
                <p class="text-slate-500 text-[10px] uppercase font-bold tracking-widest mb-1">War Ready Hari Ini</p>
                <h3 class="text-3xl font-black text-green-500">{{ number_format($globalStats['warReadyCount']) }}</h3>
                <p class="text-[9px] text-slate-600 mt-2 italic">Siap tempur di medan perang</p>
            </div>
            <div class="stat-card p-6 rounded-2xl border border-white/5 text-center">
                <p class="text-slate-500 text-[10px] uppercase font-bold tracking-widest mb-1">Paling Banyak Disarankan
                </p>
                <h3 class="text-2xl font-black text-blue-400 truncate">{{ $globalStats['topRecommendedTroop'] }}</h3>
                <p class="text-[9px] text-slate-600 mt-2 italic">Prioritas upgrade global saat ini</p>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 px-6 bg-gray-900/20">
        <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="space-y-4">
                <div
                    class="w-10 h-10 bg-orange-500/20 rounded-lg flex items-center justify-center text-orange-500 font-bold">
                    1</div>
                <h3 class="text-lg font-bold">Rekomendasi Dinamis</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Analisis mendalam terhadap status Hero, Troop, dan
                    Spell untuk menentukan prioritas upgrade paling efisien.</p>
            </div>
            <div class="space-y-4">
                <div
                    class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center text-blue-500 font-bold">
                    2</div>
                <h3 class="text-lg font-bold">Kesiapan War</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Algoritma khusus untuk menentukan apakah akun Anda
                    sudah siap untuk War tingkat tinggi atau perlu peningkatan.</p>
            </div>
            <div class="space-y-4">
                <div
                    class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center text-purple-500 font-bold">
                    3</div>
                <h3 class="text-lg font-bold">Saran Strategi</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Saran Meta, Set Gear, dan Super Troop yang paling cocok
                    untuk level Town Hall dan pasukan Anda.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 border-t border-gray-800/50 text-center px-6 opacity-40">
        <p class="text-gray-500 text-[10px] mb-4 max-w-2xl mx-auto leading-relaxed uppercase tracking-tighter">
            Website ini bukan aplikasi resmi Supercell. Data diambil untuk tujuan analisis strategi komunitas.
        </p>
        <p class="text-gray-400 text-[10px] font-bold">&copy; {{ date('Y') }} CoC Analyzer Platform</p>
    </footer>
</body>

</html>