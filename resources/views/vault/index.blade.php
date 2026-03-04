<x-app-layout :suppress-global-nav="false">
    <div class="pt-24 pb-32 bg-[#080808] min-h-screen text-white font-sans overflow-x-hidden" 
         x-data="{ 
            showSaveModal: false,
            selectedHolding: null,
            saveGrams: '',
            processing: false,
            message: '',
            status: '',
            async submitSave() {
                if (!this.saveGrams || this.saveGrams <= 0) return;
                this.processing = true;
                
                try {
                    const response = await fetch('{{ route('vault.save') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            holding_id: this.selectedHolding.id,
                            grams: this.saveGrams
                        })
                    });
                    
                    const result = await response.json();
                    this.status = result.success ? 'success' : 'error';
                    this.message = result.message;
                    
                    if (result.success) {
                        setTimeout(() => window.location.reload(), 2000);
                    }
                } catch (e) {
                    this.status = 'error';
                    this.message = 'Vault commitment failed. Please try again.';
                } finally {
                    this.processing = false;
                }
            }
         }">
        
        <div class="max-w-7xl mx-auto px-6 lg:px-8 space-y-20">
            <!-- Institutional Header -->
            <div class="flex flex-col md:flex-row justify-between items-end gap-8">
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <span class="w-1.5 h-1.5 bg-gold rounded-full animate-pulse"></span>
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.4em]">Strategic Reserve</span>
                    </div>
                    <div class="flex items-center gap-6">
                        <h1 class="text-5xl font-black tracking-tighter uppercase leading-none">Digital <span class="text-gold">Vault</span></h1>
                        <div class="luxury-card px-4 py-2 border-gold/20 bg-gold/5 flex flex-col justify-center">
                            <span class="text-[8px] font-black text-gold uppercase tracking-widest">Total Vaulted Value</span>
                            <span class="text-lg font-black">{{ \App\Services\CurrencyService::format($totalVaultValue) }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-6">
                    <div class="text-right">
                        <p class="text-[9px] font-bold text-gray-600 uppercase tracking-widest">Available Balance</p>
                        <p class="text-xl font-black text-white">{{ \App\Services\CurrencyService::formatNative($user->balance) }}</p>
                    </div>
                </div>
            </div>

            <!-- Savings Analytics Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Yield Aperture Card -->
                <div class="lg:col-span-1 luxury-card p-10 space-y-10 flex flex-col items-center justify-center text-center">
                    <div class="relative group">
                        <div class="w-48 h-48 rounded-full border-[15px] border-white/5 flex flex-col items-center justify-center">
                            <span class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">Growth Rate</span>
                            <span class="text-4xl font-black text-gold">4.9%</span>
                            <span class="text-[8px] font-black text-gray-600 uppercase tracking-widest">AVG APY</span>
                        </div>
                        <svg class="absolute inset-0 w-48 h-48 -rotate-90">
                            <circle cx="96" cy="96" r="88" fill="none" stroke="#d4af37" stroke-width="15" stroke-dasharray="450 550" stroke-linecap="round" class="opacity-20" />
                        </svg>
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-xs font-black uppercase tracking-widest text-gray-400">Yield Optimization</h3>
                        <p class="text-gray-500 text-[10px] leading-relaxed max-w-[200px]">Institutional-grade asset appreciation powered by custodial liquidity protocols.</p>
                    </div>
                </div>

                <!-- Savings Table -->
                <div class="lg:col-span-2 space-y-8">
                    <h2 class="text-xs font-black uppercase tracking-[0.5em] text-gold/60">Active Yield Positions</h2>
                    <div class="space-y-4">
                        @forelse($vaultSavings as $saving)
                        <div class="bg-white/5 border border-white/10 rounded-3xl p-8 flex flex-col md:flex-row justify-between items-center group hover:border-gold/30 transition-all duration-500 gap-8">
                            <div class="flex items-center space-x-6">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br {{ $saving['metal'] == 'gold' ? 'from-gold/20 to-orange-500/20' : 'from-gray-400/20 to-gray-600/20' }} flex items-center justify-center text-sm font-black text-white border border-white/5">
                                    {{ strtoupper(substr($saving['metal'], 0, 2)) }}
                                </div>
                                <div>
                                    <h4 class="text-lg font-black uppercase tracking-tighter">{{ $saving['name'] }}</h4>
                                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Vaulted on {{ \Carbon\Carbon::parse($saving['started_at'])->format('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-12 text-center md:text-right">
                                <div>
                                    <p class="text-[9px] font-bold text-gray-600 uppercase tracking-widest">HODL VOLUME</p>
                                    <p class="text-xl font-black">{{ number_format($saving['quantity'], 2) }}g</p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-gray-600 uppercase tracking-widest">MONTHLY YIELD</p>
                                    <p class="text-xl font-black text-green-500">{{ \App\Services\CurrencyService::format($saving['monthly_yield']) }}</p>
                                </div>
                                <div class="px-4 py-2 bg-gold/10 border border-gold/20 rounded-full">
                                    <span class="text-[10px] font-black text-gold">{{ $saving['yield_rate'] }}% APY</span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="py-20 text-center border-2 border-dashed border-white/5 rounded-[2.5rem]">
                            <p class="text-gray-600 italic text-sm">No assets currently earning yield in your private vault.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Flexible Holdings (Source for Savings) -->
            <div class="space-y-10 pt-10 border-t border-white/5">
                <div class="flex justify-between items-center">
                    <h2 class="text-xs font-black uppercase tracking-[0.5em] text-gray-500">Flexible Asset Registry</h2>
                    <p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest">Available for Vault Commitment</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse($flexibleHoldings as $holding)
                    <div class="luxury-card p-10 flex flex-col justify-between group hover:border-gold/50 transition-all duration-500">
                        <div class="flex justify-between items-start mb-12">
                            <div class="space-y-1">
                                <span class="px-3 py-1 bg-white/5 border border-white/10 rounded-full text-[9px] font-bold text-gray-400 uppercase tracking-widest">Trade Balance</span>
                                <h3 class="text-xl font-bold uppercase tracking-tighter group-hover:text-gold transition-colors">{{ $holding['name'] }}</h3>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest">QUANTITY</p>
                                <p class="text-lg font-black">{{ number_format($holding['quantity'], 2) }}g</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-6">
                            <div>
                                <p class="text-[9px] font-bold text-gray-600 uppercase tracking-widest">EST. VALUE</p>
                                <p class="text-xl font-black">{{ \App\Services\CurrencyService::format($holding['current_value']) }}</p>
                            </div>
                            <button @click="selectedHolding = {{ json_encode($holding) }}; showSaveModal = true" class="px-6 py-3 bg-white text-black text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-gold transition-all">
                                Save to Vault
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-16 text-center bg-white/[0.02] border border-white/5 rounded-3xl">
                        <p class="text-gray-600 text-sm">No flexible holdings available for vault commitment.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Commitment Modal -->
        <div x-show="showSaveModal" 
             class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/95 backdrop-blur-2xl"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             x-cloak>
            
            <div class="luxury-card w-full max-w-lg p-12 relative overflow-hidden" @click.away="showSaveModal = false">
                <div class="relative space-y-8">
                    <div class="flex justify-between items-start">
                        <div class="space-y-2">
                            <h3 class="text-2xl font-black uppercase tracking-tighter">Vault Commitment</h3>
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.3em]">HODL & EARN PROTOCOL</p>
                        </div>
                        <button @click="showSaveModal = false" class="text-gray-500 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <div class="space-y-6">
                        <div class="p-6 bg-white/5 border border-white/10 rounded-2xl flex justify-between items-center">
                            <div>
                                <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-1">Asset Asset</p>
                                <p class="text-lg font-black uppercase" x-text="selectedHolding?.name"></p>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-1">Target Yield</p>
                                <p class="text-lg font-black text-gold" x-text="selectedHolding?.metal === 'gold' ? '4.5% APY' : '5.5% APY'"></p>
                            </div>
                        </div>

                        <div class="relative">
                            <input type="number" 
                                   x-model="saveGrams"
                                   :placeholder="'MAX ' + (selectedHolding?.quantity || 0)"
                                   class="w-full bg-black border border-white/10 rounded-2xl py-8 px-8 text-4xl font-black focus:border-gold focus:ring-0 transition-all placeholder-gray-800">
                            <span class="absolute right-8 top-1/2 -translate-y-1/2 text-xs font-black text-gray-500 tracking-widest">GRAMS</span>
                        </div>
                    </div>

                    <!-- CTA -->
                    <div class="pt-8 border-t border-white/5 space-y-6">
                        <div x-show="message" 
                             :class="status === 'success' ? 'bg-green-500/10 text-green-500 border-green-500/20' : 'bg-red-500/10 text-red-500 border-red-500/20'"
                             class="p-4 rounded-xl border text-[10px] font-bold uppercase tracking-widest text-center"
                             x-text="message"></div>

                        <button @click="submitSave()"
                                :disabled="processing || !saveGrams || Number(saveGrams) <= 0"
                                class="w-full bg-white text-black py-4 rounded-2xl font-black uppercase tracking-widest text-sm transition-all hover:bg-gold flex items-center justify-center space-x-3">
                            <span x-show="!processing">Authorize Commitment</span>
                            <span x-show="processing" class="flex items-center space-x-2">
                                <svg class="animate-spin h-4 w-4 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span>Securing Assets...</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <x-bottom-nav-dock />
    </div>
</x-app-layout>
