<x-app-layout :suppress-global-nav="false">
    <div class="pt-24 pb-32 bg-[#080808] min-h-screen text-white font-sans overflow-x-hidden"
         x-data="{ 
            amount: '',
            processing: false,
            message: '',
            status: '',
            currencySymbol: '{{ \App\Services\CurrencyService::getSymbol() }}',
            async submitFunding() {
                if (!this.amount || this.amount < 1) return;
                this.processing = true;
                this.message = '';
                
                try {
                    const response = await fetch('{{ route('capital.fund') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            amount: this.amount
                        })
                    });
                    
                    const result = await response.json();
                    this.status = result.success ? 'success' : 'error';
                    this.message = result.message;
                    
                    if (result.success) {
                        setTimeout(() => window.location.href = result.redirect_url || '{{ route('dashboard') }}', 2000);
                    }
                } catch (e) {
                    this.status = 'error';
                    this.message = 'Capital injection failed. Please try again.';
                } finally {
                    this.processing = false;
                }
            }
         }">
        
        <div class="max-w-7xl mx-auto px-6 lg:px-8 space-y-16">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row justify-between items-end gap-8">
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <span class="w-1.5 h-1.5 bg-gold rounded-full animate-pulse"></span>
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.4em]">Capital Infrastructure</span>
                    </div>
                    <h1 class="text-5xl font-black tracking-tighter uppercase leading-none">Fund <span class="text-gold">Balance</span></h1>
                </div>

                <div class="flex items-center space-x-4 bg-white/5 rounded-full pl-6 pr-2 py-2 border border-white/5 backdrop-blur-md">
                    <div class="text-right">
                        <p class="text-[9px] font-bold text-gray-600 uppercase tracking-widest">Available Balance</p>
                        <p class="text-xl font-black text-white">{{ \App\Services\CurrencyService::format($user->balance) }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-gold flex items-center justify-center text-black">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>
            </div>

            <!-- Funding Interface -->
            <div class="max-w-xl mx-auto">
                <div class="luxury-card p-12 space-y-10 relative overflow-hidden">
                    <div class="absolute -top-24 -right-24 w-48 h-48 bg-gold/5 rounded-full blur-3xl"></div>
                    
                    <div class="space-y-8 relative">
                        <div class="space-y-2">
                            <h3 class="text-2xl font-black uppercase tracking-tighter text-center">Inject Liquidity</h3>
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.3em] text-center">Institutional Settlement Protocol</p>
                        </div>

                        <!-- Amount Input -->
                        <div class="space-y-4">
                            <div class="relative">
                                <input type="number" 
                                       x-model="amount"
                                       placeholder="0.00"
                                       class="w-full bg-black border border-white/10 rounded-2xl py-8 px-8 text-5xl font-black text-center focus:border-gold focus:ring-0 transition-all placeholder-gray-800">
                                <span class="absolute left-8 top-1/2 -translate-y-1/2 text-xs font-black text-gray-500 tracking-widest" x-text="currencySymbol === '$' ? 'USD' : 'GBP'"></span>
                            </div>
                            
                            <div class="grid grid-cols-4 gap-3">
                                <button @click="amount = 100" class="py-3 bg-white/5 border border-white/10 rounded-xl text-[10px] font-black hover:bg-white hover:text-black transition-all" x-text="currencySymbol + '100'"></button>
                                <button @click="amount = 500" class="py-3 bg-white/5 border border-white/10 rounded-xl text-[10px] font-black hover:bg-white hover:text-black transition-all" x-text="currencySymbol + '500'"></button>
                                <button @click="amount = 1000" class="py-3 bg-white/5 border border-white/10 rounded-xl text-[10px] font-black hover:bg-white hover:text-black transition-all" x-text="currencySymbol + '1,000'"></button>
                                <button @click="amount = 5000" class="py-3 bg-white/5 border border-white/10 rounded-xl text-[10px] font-black hover:bg-white hover:text-black transition-all" x-text="currencySymbol + '5,000'"></button>
                            </div>
                        </div>

                        <!-- CTA Section -->
                        <div class="space-y-6 pt-4">
                            <div x-show="message" 
                                 :class="status === 'success' ? 'bg-green-500/10 text-green-500 border-green-500/20' : 'bg-red-500/10 text-red-500 border-red-500/20'"
                                 class="p-4 rounded-xl border text-[10px] font-bold uppercase tracking-widest text-center"
                                 x-text="message"
                                 x-transition></div>

                            <button @click="submitFunding()"
                                    :disabled="processing || !amount || amount < 1"
                                    class="w-full bg-white text-black py-5 rounded-2xl font-black uppercase tracking-widest text-sm transition-all hover:bg-gold flex items-center justify-center space-x-3 shadow-2xl shadow-white/5">
                                <span x-show="!processing">Authorize Capital Funding</span>
                                <span x-show="processing" class="flex items-center space-x-2">
                                    <svg class="animate-spin h-4 w-4 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    <span>Processing Settlement...</span>
                                </span>
                            </button>

                            <p class="text-[8px] font-bold text-gray-600 uppercase tracking-widest text-center">
                                By authorizing, you agree to the institutional liquidity terms and settlement protocols.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Link -->
            <div class="pt-8 flex justify-center">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 text-[10px] font-bold text-gray-500 hover:text-white transition-colors group">
                    <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    <span class="uppercase tracking-[0.2em]">Return to Vault Dashboard</span>
                </a>
            </div>
        </div>

        <x-bottom-nav-dock />
    </div>
</x-app-layout>
