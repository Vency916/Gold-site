<x-app-layout>
    <div class="relative min-h-screen bg-luxury-black pb-32">
        <!-- Editorial Background Element -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-[10%] -right-[10%] w-[60%] h-[60%] bg-gold/[0.03] blur-[120px] rounded-full"></div>
            <div class="absolute top-[40%] -left-[10%] w-[40%] h-[40%] bg-luxury-bronze/[0.02] blur-[100px] rounded-full"></div>
        </div>

        <!-- Header: Editorial Style -->
        <div class="relative pt-24 pb-8 px-6">
            <div class="max-w-7xl mx-auto">
                <div class="space-y-4">
                    <span class="text-[10px] font-bold uppercase tracking-[0.5em] text-gold/60 block">Secure Transaction</span>
                    <h1 class="text-5xl md:text-7xl font-black tracking-tighter uppercase leading-none">
                        Asset <span class="gold-gradient-text">Registry.</span>
                    </h1>
                    <div class="h-[1px] w-24 bg-gold/30"></div>
                </div>
            </div>
        </div>

        <div class="relative max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                <!-- Cart Items Container -->
                <div class="lg:col-span-8 space-y-6">
                    @forelse($cartItems as $item)
                    <div class="luxury-card group relative p-6 md:p-8 flex flex-col md:flex-row items-center md:items-start gap-8 transition-all duration-500 hover:border-gold/20">
                        <!-- Institutional Visual Protocol -->
                        <div class="shrink-0 w-24 h-24 bg-gradient-to-br from-luxury-charcoal to-black flex items-center justify-center border border-white/5 shadow-2xl overflow-hidden rounded-3xl">
                             @if(isset($item['image_path']) && $item['image_path'])
                                 <img src="{{ Storage::url($item['image_path']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-contain">
                             @else
                                 <div class="text-4xl font-black text-gold/10 uppercase">{{ strtoupper(substr($item['metal'], 0, 2)) }}</div>
                             @endif
                        </div>

                        <div class="flex-1 text-center md:text-left space-y-2">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div>
                                    <h3 class="text-xl md:text-2xl font-bold tracking-tight text-white group-hover:text-gold transition-colors">{{ $item['name'] }}</h3>
                                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-1">{{ $item['weight'] }}g — 999.9 Fine {{ $item['metal'] }}</p>
                                </div>
                                
                                <div class="text-right">
                                    <p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest mb-1">Total Value</p>
                                    <p class="text-2xl font-black text-white">{{ \App\Services\CurrencyService::format($item['subtotal']) }}</p>
                                    <p class="text-[9px] text-gray-500 font-mono tracking-tighter uppercase">Qty: {{ $item['quantity'] }} @ {{ \App\Services\CurrencyService::format($item['unit_price']) }}</p>
                                </div>
                            </div>

                            <div class="pt-6 flex justify-between items-center border-t border-white/5 mt-4">
                                <div class="flex items-center space-x-2">
                                    <div class="w-1 h-1 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.5)]"></div>
                                    <span class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">In Stock / Insured</span>
                                </div>
                                
                                <form action="{{ route('cart.remove') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item['id'] }}">
                                    <button type="submit" class="text-[10px] font-bold text-gray-600 hover:text-red-500 uppercase tracking-widest transition-colors flex items-center gap-2 group/btn">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        Remove Entry
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="luxury-card p-24 text-center space-y-8 animate-fade-in">
                        <div class="space-y-4">
                            <h3 class="text-4xl font-black tracking-tighter uppercase">Registry Empty.</h3>
                            <p class="text-gray-500 font-light max-w-sm mx-auto">No assets have been recorded in your current session. Begin your collection today.</p>
                        </div>
                        <a href="{{ url('/') }}" class="inline-block bg-white text-black text-[10px] font-bold px-12 py-5 uppercase tracking-[0.3em] hover:bg-gold transition-all duration-500">
                            Research Collection
                        </a>
                    </div>
                    @endforelse
                </div>

                <!-- Sticky Summary Sidebar -->
                @if(count($cartItems) > 0)
                <div class="lg:col-span-4 lg:sticky lg:top-32 space-y-6">
                    <div class="luxury-card p-8 space-y-8">
                        <h3 class="text-[10px] font-bold uppercase tracking-[0.4em] text-gold/60 border-b border-white/5 pb-6">Final Valuation</h3>
                        
                        <div class="space-y-6">
                            <div class="space-y-4 text-[11px] font-bold uppercase tracking-widest">
                                <div class="flex justify-between text-gray-500">
                                    <span>Portfolio Subtotal</span>
                                    <span class="text-white">{{ \App\Services\CurrencyService::format($total) }}</span>
                                </div>
                                <div class="flex justify-between text-gray-500">
                                    <span>Insured Shipping</span>
                                    <span class="text-gold">Complimentary</span>
                                </div>
                                <div class="flex justify-between text-gray-500">
                                    <span>Vault Storage</span>
                                    <span class="text-gold">Complimentary</span>
                                </div>
                            </div>

                            <div class="pt-8 border-t border-white/10 space-y-2">
                                <div class="flex justify-between items-baseline">
                                    <span class="text-[9px] font-bold text-gray-500 uppercase tracking-[0.5em]">Total Value</span>
                                    <span class="text-4xl font-black text-white">{{ \App\Services\CurrencyService::format($total) }}</span>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('checkout.details') }}" method="GET">
                            <button type="submit" class="w-full bg-white text-black text-[10px] font-black py-6 uppercase tracking-[0.4em] hover:bg-gold transition-all duration-500 active:scale-95 shadow-xl shadow-white/5">
                                {{ Auth::check() ? 'Commit to Purchase' : 'Sign in to Purchase' }}
                            </button>
                        </form>
                        
                        <div class="pt-6 border-t border-white/5 space-y-4">
                            <div class="flex justify-center items-center space-x-6">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="text-[7px] font-black text-gray-600 uppercase tracking-widest">Security</span>
                                    <span class="text-[8px] font-bold text-gold uppercase tracking-[0.2em]">SSL Encrypted</span>
                                </div>
                                <div class="h-6 w-[1px] bg-white/5"></div>
                                <div class="flex flex-col items-center gap-1">
                                    <span class="text-[7px] font-black text-gray-600 uppercase tracking-widest">Sourcing</span>
                                    <span class="text-[8px] font-bold text-gold uppercase tracking-[0.2em]">LBMA Certified</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
