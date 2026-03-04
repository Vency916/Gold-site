<x-guest-layout>
    <div class="space-y-8">
        <div class="text-center">
            <h2 class="text-2xl font-black tracking-tighter uppercase text-white">Secure Access</h2>
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-2">Enter your credentials to access the vault</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div class="space-y-2">
                <label for="email" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Institutional Email</label>
                <input id="email" 
                       class="w-full bg-black/40 border border-white/10 rounded-none px-4 py-3 text-sm text-white focus:border-gold focus:ring-1 focus:ring-gold/20 transition-all font-sans" 
                       type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Password -->
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <label for="password" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Access Key</label>
                    @if (Route::has('password.request'))
                        <a class="text-[9px] font-bold text-gray-600 hover:text-gold uppercase tracking-widest transition-colors" href="{{ route('password.request') }}">
                            {{ __('Recovery') }}
                        </a>
                    @endif
                </div>

                <input id="password" 
                       class="w-full bg-black/40 border border-white/10 rounded-none px-4 py-3 text-sm text-white focus:border-gold focus:ring-1 focus:ring-gold/20 transition-all font-sans" 
                       type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                    <input id="remember_me" type="checkbox" class="w-4 h-4 bg-black/40 border-white/10 text-gold focus:ring-offset-luxury-black focus:ring-gold rounded-none" name="remember">
                    <span class="ms-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest group-hover:text-gray-300 transition-colors">{{ __('Maintain Session') }}</span>
                </label>
            </div>

            <div class="pt-4 border-t border-white/5 space-y-4">
                <button type="submit" class="w-full bg-white text-black text-[10px] font-black py-4 uppercase tracking-[0.4em] hover:bg-gold transition-all duration-500 active:scale-95 shadow-xl shadow-white/5">
                    {{ __('Establish Session') }}
                </button>
                
                <div class="text-center">
                    <a href="{{ route('register') }}" class="text-[9px] font-bold text-gray-600 hover:text-white uppercase tracking-widest transition-colors">
                        New Registrant? Create Account
                    </a>
                </div>
            </div>
        </form>
    </div>
</x-guest-layout>
