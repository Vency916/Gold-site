<x-guest-layout>
    <div class="space-y-8">
        <div class="text-center">
            <h2 class="text-2xl font-black tracking-tighter uppercase text-white">Access Recovery</h2>
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-2">Initialize key derivation protocol</p>
        </div>

        <div class="text-[11px] font-bold text-gray-400 uppercase tracking-widest leading-loose text-center">
            {{ __('Specify your registered email address to receive a secure recovery link.') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div class="space-y-2">
                <label for="email" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Institutional Email</label>
                <input id="email" 
                       class="w-full bg-black/40 border border-white/10 rounded-none px-4 py-3 text-sm text-white focus:border-gold focus:ring-1 focus:ring-gold/20 transition-all font-sans" 
                       type="email" name="email" :value="old('email')" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <div class="pt-6 border-t border-white/5 space-y-4">
                <button type="submit" class="w-full bg-white text-black text-[10px] font-black py-4 uppercase tracking-[0.4em] hover:bg-gold transition-all duration-500 active:scale-95 shadow-xl shadow-white/5">
                    {{ __('Dispatch Recovery Key') }}
                </button>
                
                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-[9px] font-bold text-gray-600 hover:text-white uppercase tracking-widest transition-colors">
                        Return to Secure Access
                    </a>
                </div>
            </div>
        </form>
    </div>
</x-guest-layout>
