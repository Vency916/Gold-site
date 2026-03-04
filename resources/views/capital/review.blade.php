<x-app-layout>
    <div class="relative min-h-screen bg-luxury-black pb-32">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-[20%] left-[10%] w-[50%] h-[50%] bg-gold/[0.02] blur-[150px] rounded-full"></div>
        </div>

        <div class="relative pt-24 pb-8 px-6">
            <div class="max-w-3xl mx-auto">
                <div class="space-y-4 text-center">
                    <span class="text-[10px] font-bold uppercase tracking-[0.5em] text-gold/60 block">Capital Settlement Step 03</span>
                    <h1 class="text-4xl md:text-5xl font-black tracking-tighter uppercase leading-none text-white">
                        Final <span class="gold-gradient-text">Review.</span>
                    </h1>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-4">Confirm institutional liquidity injection</p>
                </div>
            </div>
        </div>

        <div class="relative max-w-3xl mx-auto px-6 lg:px-8">
            <div class="luxury-card p-8 md:p-12 space-y-12">
                <!-- Funding Summary -->
                <div class="space-y-8">
                    <div class="flex items-center justify-between border-b border-white/5 pb-6">
                        <div class="space-y-1">
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Injection Amount</p>
                            <p class="text-3xl font-black text-white tracking-tighter">{{ \App\Services\CurrencyService::formatNative($fundingData['amount']) }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-gold/10 flex items-center justify-center text-gold">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Settlement Channel</p>
                            <div class="flex items-center space-x-3 text-white">
                                <span class="text-sm font-black uppercase tracking-wider">
                                    {{ str_replace('_', ' ', $fundingData['payment_method']) }}
                                </span>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                                {{ $fundingData['payment_method'] === 'bank_transfer' ? 'Settlement Currency' : 'Asset Network' }}
                            </p>
                            <p class="text-sm font-black text-gold uppercase tracking-wider">
                                {{ $fundingData['payment_currency'] ?? $fundingData['payment_network'] }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Terms & Authorization -->
                <div class="bg-white/5 rounded-3xl p-8 border border-white/10 space-y-6">
                    <div class="flex items-start space-x-4">
                        <div class="mt-1">
                            <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase leading-loose tracking-widest">By authorizing this injection, you acknowledge that funds will clear into your institutional balance only after receipt verification by the clearing department.</p>
                    </div>
                </div>

                <form action="{{ route('capital.fund.complete') }}" method="POST" class="pt-8 flex flex-col md:flex-row gap-4">
                    @csrf
                    <a href="{{ route('capital.fund.payment') }}" class="flex-1 text-center bg-white/5 text-gray-400 text-[10px] font-bold py-6 uppercase tracking-[0.3em] hover:bg-white/10 transition-all duration-500 rounded-2xl">
                        Adjust Protocol
                    </a>
                    <button type="submit" class="flex-[2] bg-white text-black text-[10px] font-black py-6 uppercase tracking-[0.4em] hover:bg-gold transition-all duration-500 active:scale-95 shadow-2xl shadow-white/5 rounded-2xl">
                        Authorize Settlement Injection
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
