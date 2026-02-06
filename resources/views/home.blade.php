<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analisis Player CoC | Beranda</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
        }
    </style>
</head>

<body class="bg-[#0b0e14] text-gray-100 min-h-screen">
    <!-- Hero Section -->
    <header class="relative py-20 px-6 overflow-hidden">
        <div
            class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[500px] bg-gradient-to-b from-orange-500/10 to-transparent -z-10">
        </div>

        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-5xl md:text-7xl font-black mb-6 tracking-tight">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-orange-500">
                    Clash of Clans
                </span><br>
                Player Analyzer
            </h1>
            <p class="text-gray-400 text-xl md:text-2xl mb-12 max-w-2xl mx-auto leading-relaxed">
                Cek Level Hero, Equipment, dan dapatkan Rekomendasi Upgrade secara instan.
            </p>

            <div class="max-w-lg mx-auto bg-gray-900/50 p-2 rounded-2xl border border-gray-800 shadow-2xl glass mb-4">
                <form action="{{ route('player.search') }}" method="GET" class="flex flex-col md:flex-row gap-2">
                    <div class="relative flex-grow">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 font-bold">#</span>
                        <input type="text" name="tag" placeholder="RR9YGRVJ"
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl py-4 pl-8 pr-4 outline-none focus:ring-2 focus:ring-orange-500 transition-all">
                    </div>
                    <button type="submit"
                        class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg transition-all active:scale-95 whitespace-nowrap">
                        Analisis Player
                    </button>
                </form>
            </div>

            @if(session('error'))
                <p class="text-red-500 text-sm font-medium">{{ session('error') }}</p>
            @endif
        </div>
    </header>

    <!-- Features Section -->
    <section class="py-20 px-6 bg-gray-900/30">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Feature 1 -->
                <div
                    class="bg-gray-800/50 p-8 rounded-2xl border border-gray-700 hover:border-orange-500/30 transition-all">
                    <div
                        class="w-12 h-12 bg-orange-500/20 rounded-xl flex items-center justify-center text-orange-500 mb-6 font-bold text-xl">
                        1</div>
                    <h3 class="text-xl font-bold mb-3">Pelacak Hero</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">Pantau level hero dan progres menuju level maksimal
                        dengan indikator visual.</p>
                </div>
                <!-- Feature 2 -->
                <div
                    class="bg-gray-800/50 p-8 rounded-2xl border border-gray-700 hover:border-purple-500/30 transition-all">
                    <div
                        class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center text-purple-500 mb-6 font-bold text-xl">
                        2</div>
                    <h3 class="text-xl font-bold mb-3">Analisis Equipment</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">Lihat semua perlengkapan hero. Ketahui apa yang
                        sudah maksimal dan apa selanjutnya.</p>
                </div>
                <!-- Feature 3 -->
                <div
                    class="bg-gray-800/50 p-8 rounded-2xl border border-gray-700 hover:border-blue-500/30 transition-all">
                    <div
                        class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center text-blue-500 mb-6 font-bold text-xl">
                        3</div>
                    <h3 class="text-xl font-bold mb-3">Saran Cerdas</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">Dapatkan rekomendasi upgrade otomatis berdasarkan
                        status Town Hall Anda saat ini.</p>
                </div>
                <!-- Feature 4 -->
                <div
                    class="bg-gray-800/50 p-8 rounded-2xl border border-gray-700 hover:border-green-500/30 transition-all">
                    <div
                        class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center text-green-500 mb-6 font-bold text-xl">
                        4</div>
                    <h3 class="text-xl font-bold mb-3">Sistem Cache</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">Sistem cache 24 jam yang dioptimalkan memastikan
                        pemuatan halaman yang kilat.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How it works -->
    <section class="py-20 px-6">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl font-bold mb-16">Cara Kerja</h2>
            <div class="flex flex-col md:flex-row items-center justify-between gap-12">
                <div class="flex-1">
                    <div class="text-4xl font-black text-gray-800 mb-4">LANGKAH 01</div>
                    <p class="text-gray-300">Masukkan player tag resmi Anda dari dalam game.</p>
                </div>
                <div class="hidden md:block text-orange-500">→</div>
                <div class="flex-1">
                    <div class="text-4xl font-black text-gray-800 mb-4">LANGKAH 02</div>
                    <p class="text-gray-300">Tunggu saat kami mengambil data profil live Anda.</p>
                </div>
                <div class="hidden md:block text-orange-500">→</div>
                <div class="flex-1">
                    <div class="text-4xl font-black text-gray-800 mb-4">LANGKAH 03</div>
                    <p class="text-gray-300">Tinjau statistik dan jalur upgrade Anda.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 border-t border-gray-800 text-center px-6">
        <div class="max-w-4xl mx-auto">
            <p class="text-gray-500 text-xs mb-4 leading-relaxed">
                Konten ini tidak berafiliasi dengan, didukung, disponsori, atau disetujui secara khusus oleh Supercell
                dan Supercell tidak bertanggung jawab atas hal tersebut. Untuk informasi lebih lanjut lihat Kebijakan
                Konten Penggemar Supercell: www.supercell.com/fan-content-policy.
            </p>
            <p class="text-gray-400 text-sm">&copy; {{ date('Y') }} Analisis CoC</p>
        </div>
    </footer>
</body>

</html>