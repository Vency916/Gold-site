<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Login | {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-gray-900 admin-body">
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
            <div class="orbit-card px-8 py-10">
                <div class="mb-10 text-center">
                    <h2 class="text-sm font-black uppercase tracking-[0.2em] text-gray-900">Access Verification</h2>
                    <p class="text-[9px] font-bold text-gray-400 mt-2 uppercase tracking-widest">Protocol strictly monitored</p>
                </div>

                <form class="space-y-6" action="{{ route('admin.login.post') }}" method="POST">
                    @csrf
                    <div class="space-y-2">
                        <label for="email" class="block text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Personnel Identifier</label>
                        <input id="email" name="email" type="email" autocomplete="email" placeholder="master@orbit.com" required 
                               class="orbit-input w-full font-bold">
                        @error('email')
                            <p class="mt-2 text-[10px] font-black text-red-500 uppercase tracking-widest transition-all animate-pulse">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="block text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Secure Keyhole</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" placeholder="••••••••" required 
                               class="orbit-input w-full font-bold">
                        @error('password')
                            <p class="mt-2 text-[10px] font-black text-red-500 uppercase tracking-widest transition-all animate-pulse">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <input id="remember" name="remember" type="checkbox" class="h-4 w-4 rounded border-gray-100 text-black focus:ring-0 cursor-pointer">
                            <label for="remember" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest cursor-pointer">Persist Session</label>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="orbit-btn-primary w-full py-4 shadow-xl shadow-black/5 hover:translate-y-[-1px] active:translate-y-[0px] transition-all">
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
