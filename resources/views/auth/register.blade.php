<x-guest-layout>
    <div class="space-y-8">
        <div class="text-center">
            <h2 class="text-2xl font-black tracking-tighter uppercase text-white">Registry Enrollment</h2>
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-2">Initialize your private custodial account</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <!-- Name -->
            <div class="space-y-2">
                <label for="name" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Full Legal Name</label>
                <input id="name" 
                       class="w-full bg-black/40 border border-white/10 rounded-none px-4 py-3 text-sm text-white focus:border-gold focus:ring-1 focus:ring-gold/20 transition-all font-sans" 
                       type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <!-- Email Address -->
            <div class="space-y-2">
                <label for="email" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Electronic Mail</label>
                <input id="email" 
                       class="w-full bg-black/40 border border-white/10 rounded-none px-4 py-3 text-sm text-white focus:border-gold focus:ring-1 focus:ring-gold/20 transition-all font-sans" 
                       type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Password Structure -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Password -->
                <div class="space-y-2">
                    <label for="password" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Access Key</label>
                    <input id="password" 
                           class="w-full bg-black/40 border border-white/10 rounded-none px-4 py-3 text-sm text-white focus:border-gold focus:ring-1 focus:ring-gold/20 transition-all font-sans" 
                           type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <!-- Confirm Password -->
                <div class="space-y-2">
                    <label for="password_confirmation" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Verify Key</label>
                    <input id="password_confirmation" 
                           class="w-full bg-black/40 border border-white/10 rounded-none px-4 py-3 text-sm text-white focus:border-gold focus:ring-1 focus:ring-gold/20 transition-all font-sans" 
                           type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                </div>
            </div>

            <!-- Currency Preference -->
            <div class="space-y-2">
                <label for="currency" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Institutional Currency</label>
                <select id="currency" name="currency" class="w-full bg-black/40 border border-white/10 rounded-none px-4 py-3 text-sm text-white focus:border-gold focus:ring-1 focus:ring-gold/20 transition-all font-sans appearance-none cursor-pointer">
                    <option value="USD" {{ old('currency', 'USD') === 'USD' ? 'selected' : '' }}>USD - United States Dollar</option>
                    <option value="GBP" {{ old('currency') === 'GBP' ? 'selected' : '' }}>GBP - British Pound Sterling</option>
                </select>
                <x-input-error :messages="$errors->get('currency')" class="mt-1" />
            </div>

            <div class="pt-6 border-t border-white/5 space-y-4">
                <button type="submit" class="w-full bg-white text-black text-[10px] font-black py-4 uppercase tracking-[0.4em] hover:bg-gold transition-all duration-500 active:scale-95 shadow-xl shadow-white/5">
                    {{ __('Initialize Account') }}
                </button>
                
                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-[9px] font-bold text-gray-600 hover:text-white uppercase tracking-widest transition-colors">
                        {{ __('Already registered? Establish Session') }}
                    </a>
                </div>
            </div>
        </form>
    </div>
</x-guest-layout>
