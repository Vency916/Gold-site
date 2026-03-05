<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $siteName }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

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
                            sans: ['Outfit', 'sans-serif'],
                            serif: ['Playfair Display', 'serif']
                        }
                    }
                }
            }
        </script>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- CSS Class Definitions Fallback (mirrors compiled app.css) -->
        <style>
            body { background-color: #0A0A0A; color: #e5e7eb; font-family: 'Outfit', sans-serif; }
            h1,h2,h3,h4 { font-family: 'Outfit', sans-serif; font-weight: 700; letter-spacing: -0.025em; }
            .luxury-card { background-color: #111111; border: 1px solid rgba(255,255,255,0.05); border-radius: 2rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); backdrop-filter: blur(48px); }
            .holding-card { position: relative; overflow: hidden; border-radius: 2.5rem; background-color: #0A0A0A; border: 1px solid rgba(255,255,255,0.05); transition: all 0.5s; }
            .holding-card:hover { transform: translateY(-8px); box-shadow: 0 20px 50px rgba(0,0,0,0.5); }
            .holding-card-header { padding: 2rem; background-color: #161616; border-bottom: 1px solid rgba(255,255,255,0.05); }
            .holding-card-body { padding: 2rem; }
            .bottom-dock { position: fixed; bottom: 2rem; left: 50%; transform: translateX(-50%); background: rgba(0,0,0,0.8); backdrop-filter: blur(48px); border: 1px solid rgba(255,255,255,0.1); border-radius: 9999px; padding: 1rem 2rem; display: flex; align-items: center; gap: 3rem; z-index: 100; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
            .dock-fab { width: 3.5rem; height: 3.5rem; border-radius: 9999px; background-color: #D4AF37; color: black; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 15px -3px rgba(212,175,55,0.2); margin-top: -2.5rem; border: 4px solid #0A0A0A; transition: all 0.3s; }
            .dock-fab:hover { transform: scale(1.1); }
            .dock-fab:active { transform: scale(0.95); }
            .gold-gradient-text { background: linear-gradient(135deg, #F4E0A1, #D4AF37, #996515); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-style: italic; }
            .btn-gold { background: linear-gradient(to right, #996515, #D4AF37, #996515); color: black; font-weight: 700; padding: 0.75rem 2rem; transition: all 0.5s; text-transform: uppercase; letter-spacing: 0.2em; font-size: 0.625rem; }
            .glass { background: rgba(0,0,0,0.6); backdrop-filter: blur(48px); border-color: rgba(255,255,255,0.1); }
            .glow-gold { box-shadow: 0 0 15px -2px rgba(212,175,55,0.3); }
            @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
            .animate-marquee { animation: marquee 30s linear infinite; }
            .animate-marquee:hover { animation-play-state: paused; }
            @keyframes float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
            .animate-float { animation: float 6s ease-in-out infinite; }
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="antialiased selection:bg-gold/30 bg-[#0A0A0A] text-gray-200">
        <div class="min-h-screen bg-[#0A0A0A] text-gray-200">
            @if(! ($suppressGlobalNav ?? false))
                @include('layouts.navigation')
            @endif

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
