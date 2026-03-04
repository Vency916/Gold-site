<section class="space-y-10">
    <header class="space-y-2">
        <h2 class="text-2xl font-black uppercase tracking-tighter text-red-500">
            {{ __('Termination Protocol') }}
        </h2>

        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em]">
            {{ __('Once your institutional registry is terminated, all digital assets and historical data will be permanently purged.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Initiate Termination Protocol') }}</x-danger-button>

    <template x-teleport="body">
        <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
            <form method="post" action="{{ route('profile.destroy') }}" class="relative overflow-hidden">
                @csrf
                @method('delete')

                <!-- Modal Backdrop/Glow -->
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-red-500/10 rounded-full blur-3xl"></div>

                <div class="p-8 md:p-12 bg-[#0a0a0a] text-white relative z-10 space-y-8 border border-white/5">
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3">
                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></span>
                            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.4em]">Critical Security Protocol</span>
                        </div>
                        <h2 class="text-2xl md:text-3xl font-black uppercase tracking-tighter leading-none">
                            Registry <span class="text-red-500">Termination</span> Confirmation
                        </h2>
                    </div>

                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em] leading-loose">
                        {{ __('This action is irreversible. All custodial records, vaulted assets, and session histories will be destroyed. Please provide your authorization code to proceed with purging this registry.') }}
                    </p>

                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label for="password" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">{{ __('Active Authorization Code') }}</label>
                            <input id="password" 
                                   name="password" 
                                   type="password" 
                                   class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-red-500 focus:ring-1 focus:ring-red-500/20 transition-all font-sans" 
                                   placeholder="{{ __('Security Code') }}" />
                            <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-[10px] font-bold uppercase tracking-widest text-red-500" />
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row items-center gap-4 pt-10 border-t border-white/5">
                        <button type="submit" class="w-full md:flex-1 bg-red-600 text-white text-[10px] font-black py-4 uppercase tracking-[0.4em] hover:bg-red-500 transition-all duration-500 active:scale-95 shadow-xl shadow-red-500/10 order-2 md:order-1">
                            {{ __('Finalize Termination') }}
                        </button>
                        
                        <button type="button" x-on:click="$dispatch('close')" class="w-full md:w-auto px-8 bg-white/5 text-gray-400 text-[10px] font-black py-4 uppercase tracking-[0.4em] hover:text-white transition-all order-1 md:order-2">
                            {{ __('Abort Protocol') }}
                        </button>
                    </div>
                </div>
            </form>
        </x-modal>
    </template>
</section>
