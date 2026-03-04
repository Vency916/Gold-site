<x-app-layout>
    <div class="relative min-h-screen bg-luxury-black pb-32">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-[20%] left-[10%] w-[50%] h-[50%] bg-gold/[0.02] blur-[150px] rounded-full"></div>
        </div>

        <div class="relative pt-24 pb-8 px-6">
            <div class="max-w-3xl mx-auto">
                <div class="space-y-4 text-center">
                    <span class="text-[10px] font-bold uppercase tracking-[0.5em] text-gold/60 block">Capital Settlement Step 02</span>
                    <h1 class="text-4xl md:text-5xl font-black tracking-tighter uppercase leading-none text-white">
                        Transfer <span class="gold-gradient-text">Method.</span>
                    </h1>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-4">Select your institutional funding channel</p>
                </div>
            </div>
        </div>

        <div class="relative max-w-3xl mx-auto px-6 lg:px-8">
            <div class="luxury-card p-8 md:p-12 overflow-hidden" x-data="{ method: 'bank_transfer' }">
                <form action="{{ route('capital.fund.postPayment') }}" method="POST" class="space-y-12">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @if(($settlementSettings['bank_transfer_enabled'] ?? '1') == '1')
                        <!-- Bank Transfer Option -->
                        <label class="relative group cursor-pointer">
                            <input type="radio" name="payment_method" value="bank_transfer" x-model="method" class="hidden peer">
                            <div class="h-full p-8 bg-white/5 border border-white/10 rounded-3xl transition-all duration-500 peer-checked:bg-gold/10 peer-checked:border-gold group-hover:bg-white/[0.08] flex flex-col items-center text-center space-y-4 relative overflow-hidden">
                                <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center group-hover:scale-110 transition-transform duration-500 peer-checked:bg-gold/20">
                                    <svg class="w-8 h-8 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" /></svg>
                                </div>
                                <div>
                                    <h3 class="text-xs font-black uppercase tracking-widest text-white">Bank Transfer</h3>
                                    <p class="text-[9px] font-bold text-gray-500 mt-2 uppercase tracking-tighter">Institutional Wire Settlement</p>
                                </div>
                                <div class="absolute top-4 right-4 w-5 h-5 rounded-full border border-white/20 peer-checked:border-gold flex items-center justify-center">
                                    <div class="w-2.5 h-2.5 rounded-full bg-gold opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                </div>
                            </div>
                        </label>
                        @endif

                        <!-- Crypto Transfer Option -->
                        <label class="relative group cursor-pointer">
                            <input type="radio" name="payment_method" value="crypto_transfer" x-model="method" class="hidden peer">
                            <div class="h-full p-8 bg-white/5 border border-white/10 rounded-3xl transition-all duration-500 peer-checked:bg-gold/10 peer-checked:border-gold group-hover:bg-white/[0.08] flex flex-col items-center text-center space-y-4 relative overflow-hidden">
                                <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center group-hover:scale-110 transition-transform duration-500 peer-checked:bg-gold/20">
                                    <svg class="w-8 h-8 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <div>
                                    <h3 class="text-xs font-black uppercase tracking-widest text-white">Crypto Vault</h3>
                                    <p class="text-[9px] font-bold text-gray-500 mt-2 uppercase tracking-tighter">Blockchain Settlement</p>
                                </div>
                                <div class="absolute top-4 right-4 w-5 h-5 rounded-full border border-white/20 peer-checked:border-gold flex items-center justify-center">
                                    <div class="w-2.5 h-2.5 rounded-full bg-gold opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- Dynamic Sub-Selection -->
                    <div class="space-y-6 pt-8 border-t border-white/5" x-show="method" x-collapse x-cloak>
                        <!-- For Bank Transfer -->
                        <div x-show="method === 'bank_transfer'" class="space-y-4 animate-fade-in-down">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Settlement Currency</label>
                            <div class="relative">
                                <select name="currency" class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-5 text-white focus:border-gold/50 focus:ring-0 transition-all font-sans text-sm appearance-none cursor-pointer">
                                    <option value="USD" class="bg-luxury-black">US Dollar (USD)</option>
                                    <option value="GBP" class="bg-luxury-black">British Pound Sterling (GBP)</option>
                                    <option value="EUR" class="bg-luxury-black">Euro (EUR)</option>
                                </select>
                                <div class="absolute inset-y-0 right-6 flex items-center pointer-events-none text-gold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </div>
                        </div>

                        <!-- For Crypto Transfer -->
                        <div x-show="method === 'crypto_transfer'" class="space-y-4 animate-fade-in-down">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Asset Network</label>
                            <div class="relative">
                                <select name="network" class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-5 text-white focus:border-gold/50 focus:ring-0 transition-all font-sans text-sm appearance-none cursor-pointer">
                                    @foreach($cryptoWallets as $wallet)
                                        <option value="{{ $wallet->network }}" class="bg-luxury-black">{{ $wallet->coin_name }} ({{ $wallet->symbol }} - {{ $wallet->network }})</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-6 flex items-center pointer-events-none text-gold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-8 flex flex-col md:flex-row gap-4">
                        <a href="{{ route('capital.index') }}" class="flex-1 text-center bg-white/5 text-gray-400 text-[10px] font-bold py-6 uppercase tracking-[0.3em] hover:bg-white/10 transition-all duration-500 rounded-2xl">
                            Back to Vault
                        </a>
                        <button type="submit" class="flex-[2] bg-white text-black text-[10px] font-black py-6 uppercase tracking-[0.4em] hover:bg-gold transition-all duration-500 active:scale-95 shadow-2xl shadow-white/5 rounded-2xl">
                            Authorize Settlement Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
