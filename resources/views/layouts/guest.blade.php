<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

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
            .luxury-card { background-color: #111111; border: 1px solid rgba(255,255,255,0.05); border-radius: 2rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); backdrop-filter: blur(48px); }
            .gold-gradient-text { background: linear-gradient(135deg, #F4E0A1, #D4AF37, #996515); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-style: italic; }
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="antialiased selection:bg-gold/30 bg-[#0A0A0A] text-gray-200">
        <div class="min-h-screen relative flex flex-col sm:justify-center items-center pt-12 sm:pt-0 px-6">
            <!-- Fluid Backdrop -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute -top-[10%] -right-[10%] w-[60%] h-[60%] bg-gold/[0.02] blur-[120px] rounded-full"></div>
                <div class="absolute top-[40%] -left-[10%] w-[40%] h-[40%] bg-white/[0.01] blur-[100px] rounded-full"></div>
            </div>

            <div class="relative z-10 w-full sm:max-w-md">
                <!-- Simple Brand Identifier -->
                <div class="text-center mb-12">
                    <a href="/" class="inline-flex items-center space-x-3 group">
                        <div class="w-10 h-10 rounded-full border border-gold flex items-center justify-center p-1 group-hover:rotate-180 transition-transform duration-1000">
                            <div class="w-full h-full rounded-full bg-gold"></div>
                        </div>
                        <span class="text-3xl font-bold tracking-tighter text-white uppercase">Golden <span class="text-gold">Vault</span></span>
                    </a>
                </div>

                <!-- Modern Luxury Card -->
                <div class="luxury-card p-8 md:p-12 shadow-2xl">
                    {{ $slot }}
                </div>

                <div class="mt-8 text-center">
                    <p class="text-[10px] font-bold text-gray-600 uppercase tracking-[0.3em]">Institutional Grade Security &bull; Asset Backed</p>
                </div>
            </div>
        </div>
    </body>
</html>
