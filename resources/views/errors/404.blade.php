<!DOCTYPE html>
<html lang="en" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>404 — Page Not Found | {{ config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- UI Lifeboat: Tailwind CSS CDN Fallback -->
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
    </head>
    <body class="antialiased bg-[#0A0A0A] text-gray-200 min-h-screen flex flex-col items-center justify-center px-6 font-sans">

        <!-- Fluid Backdrop -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-[10%] -right-[10%] w-[60%] h-[60%] bg-[#D4AF37]/[0.03] blur-[120px] rounded-full"></div>
            <div class="absolute top-[40%] -left-[10%] w-[40%] h-[40%] bg-white/[0.01] blur-[100px] rounded-full"></div>
        </div>

        <div class="relative z-10 text-center space-y-8 max-w-lg">
            <!-- Brand -->
            <a href="/" class="inline-flex items-center space-x-3 group mb-8">
                <div class="w-10 h-10 rounded-full border border-[#D4AF37] flex items-center justify-center p-1">
                    <div class="w-full h-full rounded-full bg-[#D4AF37]"></div>
                </div>
                <span class="text-2xl font-bold tracking-tighter text-white uppercase">Golden <span class="text-[#D4AF37]">Vault</span></span>
            </a>

            <!-- Error Code -->
            <div class="space-y-4">
                <p class="text-[10px] font-bold uppercase tracking-[0.5em] text-[#D4AF37]/60">Error 404</p>
                <h1 class="text-7xl md:text-9xl font-black text-white leading-none tracking-tighter">Not<br><span class="text-[#D4AF37]">Found.</span></h1>
                <p class="text-sm text-gray-500 font-light leading-relaxed max-w-sm mx-auto">
                    The page you're looking for doesn't exist or has been moved.
                </p>
            </div>

            <!-- Divider -->
            <div class="h-[1px] w-12 bg-[#D4AF37]/30 mx-auto"></div>

            <!-- Action -->
            <a href="/" class="inline-block text-[10px] font-bold uppercase tracking-[0.3em] bg-white text-black px-10 py-4 hover:bg-[#D4AF37] transition-all duration-500">
                Return Home
            </a>
        </div>

    </body>
</html>
