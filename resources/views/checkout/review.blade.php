<x-app-layout>
    <div class="relative min-h-screen bg-luxury-black pb-32">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -bottom-[10%] -left-[10%] w-[60%] h-[60%] bg-gold/[0.03] blur-[120px] rounded-full"></div>
        </div>

        <div class="relative pt-24 pb-8 px-6">
            <div class="max-w-4xl mx-auto">
                <div class="space-y-4 text-center">
                    <span class="text-[10px] font-bold uppercase tracking-[0.5em] text-gold/60 block">Acquisition Final Step</span>
                    <h1 class="text-4xl md:text-5xl font-black tracking-tighter uppercase leading-none text-white">
                        Final <span class="gold-gradient-text">Verification.</span>
                    </h1>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-4">Review your custodial acquisition summary</p>
                </div>
            </div>
        </div>

        <div class="relative max-w-4xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                <!-- Acquisition Summary -->
                <div class="lg:col-span-8 space-y-8">
                    <div class="luxury-card p-8">
                        <h3 class="text-[10px] font-bold uppercase tracking-[0.4em] text-gold/60 border-b border-white/5 pb-4 mb-8">Registry Items</h3>
                        <div class="space-y-6">
                            @foreach($cartItems as $item)
                            <div class="flex justify-between items-center bg-white/[0.02] p-4 rounded-xl border border-white/5">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-luxury-charcoal flex items-center justify-center text-[10px] font-bold text-gold">
                                        {{ strtoupper(substr($item['product']->metal, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-white">{{ $item['product']->name }}</p>
                                        <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">{{ $item['quantity'] }} x {{ \App\Services\CurrencyService::format($item['unit_price']) }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-black text-white">{{ \App\Services\CurrencyService::format($item['subtotal']) }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Shipping Credentials -->
                        <div class="luxury-card p-8">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-[10px] font-bold uppercase tracking-[0.4em] text-gold/60">Custodial Delivery</h3>
                                <a href="{{ route('checkout.details') }}" class="text-[8px] font-bold text-gray-500 hover:text-white uppercase tracking-widest transition-colors">Edit</a>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-[8px] font-bold text-gray-600 uppercase tracking-widest">Collector</p>
                                    <p class="text-xs font-bold text-white mt-1">{{ $checkoutData['shipping_name'] }}</p>
                                </div>
                                <div>
                                    <p class="text-[8px] font-bold text-gray-600 uppercase tracking-widest">Channel</p>
                                    <p class="text-xs font-bold text-white mt-1">{{ $checkoutData['shipping_phone'] }}</p>
                                </div>
                                <div>
                                    <p class="text-[8px] font-bold text-gray-600 uppercase tracking-widest">Address</p>
                                    <p class="text-xs font-bold text-white mt-1 leading-relaxed">
                                        {{ $checkoutData['shipping_address'] }}<br>
                                        {{ $checkoutData['shipping_city'] }}, {{ $checkoutData['shipping_postcode'] }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Credentials -->
                        <div class="luxury-card p-8">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-[10px] font-bold uppercase tracking-[0.4em] text-gold/60">Settlement Code</h3>
                                <a href="{{ route('checkout.payment') }}" class="text-[8px] font-bold text-gray-500 hover:text-white uppercase tracking-widest transition-colors">Edit</a>
                            </div>
                            <div class="flex items-center gap-4 bg-white/5 p-4 rounded-2xl border border-white/5">
                                <div class="w-10 h-10 rounded-full bg-gold/10 flex items-center justify-center">
                                    @if($checkoutData['payment_method'] == 'bank_transfer')
                                        <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" /></svg>
                                    @elseif($checkoutData['payment_method'] == 'cash_mailing')
                                        <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                    @else
                                        <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-white">
                                        @if($checkoutData['payment_method'] == 'bank_transfer')
                                            Bank Transfer
                                        @elseif($checkoutData['payment_method'] == 'cash_mailing')
                                            Cash Mailing
                                        @else
                                            Crypto Vault
                                        @endif
                                    </p>
                                    <p class="text-[8px] font-bold text-gray-400 uppercase tracking-tighter mt-1">
                                        @if($checkoutData['payment_method'] == 'bank_transfer')
                                            Settlement: {{ $checkoutData['payment_currency'] ?? 'Bank Wire' }}
                                        @elseif($checkoutData['payment_method'] == 'cash_mailing')
                                            Logistics: Physical Transit
                                        @else
                                            Network: {{ $checkoutData['payment_network'] ?? 'Crypto' }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Final Allocation -->
                <div class="lg:col-span-4 lg:sticky lg:top-32 h-fit">
                    <div class="luxury-card p-8 space-y-8 bg-gradient-to-br from-luxury-charcoal to-black">
                        <h3 class="text-[10px] font-bold uppercase tracking-[0.4em] text-gold/60 border-b border-white/5 pb-4">Consolidated Valuation</h3>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between text-[11px] font-bold uppercase tracking-widest">
                                <span class="text-gray-500">Registry Subtotal</span>
                                <span class="text-white">{{ \App\Services\CurrencyService::format($subtotal) }}</span>
                            </div>
                            @if(isset($discountAmount) && $discountAmount > 0)
                            <div class="flex justify-between text-[11px] font-bold uppercase tracking-widest">
                                <span class="text-gray-500">Cash Settlement Incentive ({{ $discountRate }}%)</span>
                                <span class="text-green-400">-{{ \App\Services\CurrencyService::format($discountAmount) }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between text-[11px] font-bold uppercase tracking-widest">
                                <span class="text-gray-500">Secure Logistics</span>
                                <span class="text-gold">Complimentary</span>
                            </div>
                            
                            <div class="pt-8 border-t border-white/10 mt-8">
                                <div class="flex justify-between items-baseline mb-8">
                                    <span class="text-[9px] font-bold text-gray-500 uppercase tracking-[0.5em]">Total Amount</span>
                                    <span class="text-4xl font-black text-white">{{ \App\Services\CurrencyService::format($total) }}</span>
                                </div>
                                
                                <form action="{{ route('order.checkout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full bg-gold text-black text-[10px] font-black py-6 uppercase tracking-[0.4em] hover:bg-white transition-all duration-500 active:scale-95 shadow-2xl shadow-gold/20 rounded-xl">
                                        Authorize Acquisition
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-white/5 flex flex-col items-center gap-4">
                             <div class="flex items-center gap-2">
                                <svg class="w-3 h-3 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                <span class="text-[8px] font-bold text-gray-600 uppercase tracking-[0.2em]">Institutional Encryption Active</span>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
