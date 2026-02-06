<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoC Player Search</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-900 text-gray-100 min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full bg-gray-800 rounded-2xl shadow-2xl p-8 border border-gray-700">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-orange-500">
                Clash of Clans
            </h1>
            <p class="text-gray-400 mt-2">Find player profiles and stats</p>
        </div>

        @if(session('error'))
            <div class="bg-red-500/10 border border-red-500 text-red-500 p-4 rounded-xl mb-6 flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('player.search') }}" method="GET">
            <div class="space-y-6">
                <div>
                    <label for="tag" class="block text-sm font-medium text-gray-400 mb-2">Player Tag</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 font-bold">#</span>
                        <input type="text" id="tag" name="tag" value="{{ old('tag') }}" placeholder="RR9YGRVJ"
                            class="block w-full pl-8 pr-4 py-3 bg-gray-700 border border-gray-600 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none transition-all placeholder-gray-500"
                            required>
                    </div>
                    @error('tag')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-orange-500 to-yellow-500 hover:from-orange-600 hover:to-yellow-600 text-white font-bold py-4 rounded-xl shadow-lg transform active:scale-95 transition-all">
                    Search Player
                </button>
            </div>
        </form>

        <p class="text-center text-gray-500 text-xs mt-8">
            Data provided by Clash of Clans Official API
        </p>
    </div>
</body>

</html>