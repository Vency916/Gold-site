<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Login | {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@400;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- UI Lifeboat: Tailwind CSS & Alpine.js CDN Fallbacks -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold: { light: '#F4E0A1', DEFAULT: '#D4AF37', dark: '#996515', muted: '#8A7139' },
                        luxury: { black: '#0A0A0A', charcoal: '#111111', cream: '#FAF9F6', bronze: '#CD7F32' }
                    },
                    fontFamily: {
                        sans: ['Inter', 'Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full font-sans antialiased text-gray-900 bg-[#F8F9FC]">
    <div class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center items-center gap-3">
                 <div class="w-12 h-12 rounded-2xl bg-black flex items-center justify-center shadow-xl">
                    <span class="text-white font-black text-xl italic">V.</span>
                 </div>
                 <div class="text-left">
                    <h1 class="text-xl font-black tracking-tighter uppercase leading-none">Institutional <span class="text-gray-400">Vault</span></h1>
                    <p class="text-[9px] font-bold text-gray-400 mt-1 uppercase tracking-widest">Administrative Clearance required</p>
                 </div>
            </div>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[440px]">
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm shadow-gray-100 px-8 py-10">
                <div class="mb-10 text-center">
                    <h2 class="text-sm font-black uppercase tracking-[0.2em] text-gray-900">Access Verification</h2>
                    <p class="text-[9px] font-bold text-gray-400 mt-2 uppercase tracking-widest">Protocol strictly monitored</p>
                </div>

                @if(session('error'))
                    <div class="mb-6 p-3 bg-red-50 border border-red-100 rounded-lg">
                        <p class="text-[10px] font-black text-red-500 uppercase tracking-widest">{{ session('error') }}</p>
                    </div>
                @endif

                <form class="space-y-6" action="{{ route('admin.login.post') }}" method="POST">
                    @csrf
                    <div class="space-y-2">
                        <label for="email" class="block text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Personnel Identifier</label>
                        <input id="email" name="email" type="email" autocomplete="email" placeholder="master@orbit.com" required
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-black/10 focus:border-gray-400 transition-all placeholder:text-gray-300">
                        @error('email')
                            <p class="mt-2 text-[10px] font-black text-red-500 uppercase tracking-widest">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="block text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Secure Keyhole</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" placeholder="••••••••" required
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-black/10 focus:border-gray-400 transition-all placeholder:text-gray-300">
                        @error('password')
                            <p class="mt-2 text-[10px] font-black text-red-500 uppercase tracking-widest">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <input id="remember" name="remember" type="checkbox" class="h-4 w-4 rounded border-gray-200 text-black focus:ring-0 cursor-pointer">
                            <label for="remember" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest cursor-pointer">Persist Session</label>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full py-4 bg-black text-white text-[10px] font-black uppercase tracking-[0.3em] rounded-xl shadow-xl shadow-black/10 hover:-translate-y-[1px] active:translate-y-[0px] transition-all hover:bg-gray-900">
                            Verify Identity
                        </button>
                    </div>
                </form>
            </div>

            <p class="mt-10 text-center text-[9px] font-bold text-gray-400 uppercase tracking-[0.3em] leading-loose opacity-60">
                Authorized access only.<br>Institutional monitoring protocol is active.
            </p>
        </div>
    </div>
</body>
</html>
