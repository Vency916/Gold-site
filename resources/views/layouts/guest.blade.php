<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased selection:bg-gold/30 bg-luxury-black text-gray-200">
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
