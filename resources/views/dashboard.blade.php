<x-app-layout :suppress-global-nav="false">
    <div class="pt-24 pb-32 bg-[#080808] min-h-screen text-white font-sans overflow-x-hidden" 
         x-data="{ 
            showTradeModal: false,
            tradeType: 'buy',
            tradeMetal: 'gold',
            tradeGrams: '',
            tradeBalance: {{ $user->balance }},
            goldPrice: {{ \App\Services\CurrencyService::convert($goldPrice->price) }},
            silverPrice: {{ \App\Services\CurrencyService::convert($silverPrice->price) }},
            goldGramPrice: {{ \App\Services\CurrencyService::convert($goldGramPrice) }},
            silverGramPrice: {{ \App\Services\CurrencyService::convert($silverGramPrice) }},
            currencySymbol: '{{ \App\Services\CurrencyService::getSymbol() }}',
            processing: false,
            message: '',
            status: '',
            numberFormat(val, decimals = 2) {
                return Number(val).toLocaleString(undefined, {
                    minimumFractionDigits: decimals,
                    maximumFractionDigits: decimals
                });
            },
            get gramPrice() {
                return this.tradeMetal === 'gold' ? this.goldGramPrice : this.silverGramPrice;
            },
            get totalPrice() {
                return (this.gramPrice * (this.tradeGrams || 0)).toFixed(2);
            },
            async submitTrade() {
                if (!this.tradeGrams || this.tradeGrams <= 0) return;
                this.processing = true;
                this.message = '';
                
                try {
                    const response = await fetch('{{ route('trade.process') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            metal: this.tradeMetal,
                            type: this.tradeType,
                            grams: this.tradeGrams
                        })
                    });
                    
                    const result = await response.json();
                    this.status = result.success ? 'success' : 'error';
                    this.message = result.message || (response.ok ? 'Trade executed.' : 'Institutional trade failed.');
                    
                    if (result.success) {
                        this.tradeBalance = result.new_balance;
                        setTimeout(() => {
                            if (result.redirect_url) {
                                window.location.href = result.redirect_url;
                            } else {
                                window.location.reload();
                            }
                        }, 2000);
                    }
                } catch (e) {
                    this.status = 'error';
                    this.message = 'Institutional trade failed. Please try again.';
                } finally {
                    this.processing = false;
                }
            }
         }">
        <div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-8 space-y-8 md:space-y-16">
            @php
                $goldValue = $totalGoldValue;
                $silverValue = $totalSilverValue;
                $goldPercent = $totalValue > 0 ? ($goldValue / $totalValue) * 100 : 0;
                $silverPercent = $totalValue > 0 ? ($silverValue / $totalValue) * 100 : 0;
            @endphp
            
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-8">
                <div class="space-y-4 w-full md:w-auto">
                    <div class="flex items-center space-x-3">
                        <span class="w-1.5 h-1.5 bg-gold rounded-full animate-pulse"></span>
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.4em]">Vault Dashboard</span>
                    </div>
                    <h1 class="text-3xl md:text-5xl font-black tracking-tighter uppercase leading-none">Your <span class="text-gold">Portfolio</span></h1>
                </div>

                <!-- Collaborative Metrics Grid -->
                <div class="grid grid-cols-2 md:flex md:items-center gap-4 w-full md:w-auto">  
                    <div class="luxury-card px-4 py-3 border-white/5 bg-white/5 flex flex-col justify-center">
                        <span class="text-[7px] md:text-[8px] font-black text-gray-500 uppercase tracking-[0.3em] mb-1">Available Capital</span>
                        <span class="text-lg md:text-2xl font-black text-white leading-none tracking-tighter" x-text="currencySymbol + numberFormat(tradeBalance, 2)">
                            {{ \App\Services\CurrencyService::format($user->balance) }}
                        </span>
                    </div>

                    <div class="luxury-card px-4 py-3 border-white/5 bg-gold/5 flex items-center justify-between md:justify-start gap-3 md:gap-4 md:rounded-full md:px-6">
                        <div class="text-left md:text-right">
                            <p class="text-[7px] md:text-[8px] font-black text-gray-500 md:text-gold uppercase tracking-[0.2em]">Vault Valuation</p>
                            <p class="text-lg font-black text-white leading-none tracking-tighter">{{ \App\Services\CurrencyService::format($totalValue) }}</p>
                        </div>
                        <div class="w-6 h-6 md:w-8 md:h-8 rounded-full bg-white/10 flex items-center justify-center border border-white/10 shrink-0">
                            <svg class="w-3 h-3 md:w-4 md:h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Institutional Injections Console -->
            @if($pendingDeposits->count() > 0)
            <div class="space-y-4">
                <div class="flex items-center space-x-3">
                    <span class="w-1.5 h-1.5 bg-gold rounded-full animate-pulse"></span>
                    <span class="text-[9px] font-black text-gray-500 uppercase tracking-[0.4em]">Capital Monitoring</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($pendingDeposits as $deposit)
                    <div class="luxury-card p-6 flex flex-col justify-between border-gold/10 bg-gold/[0.02] group hover:border-gold/30 transition-all duration-500">
                        <div class="flex justify-between items-start mb-6">
                            <div class="space-y-1">
                                <p class="text-[8px] font-black text-gray-600 uppercase tracking-widest">Injection Ref</p>
                                <p class="text-xs font-black text-white italic">#{{ $deposit->id }}</p>
                            </div>
                            <div class="px-3 py-1 bg-black/40 rounded-full border border-gold/20">
                                <p class="text-[7px] font-black text-gold uppercase tracking-widest">
                                    {{ str_replace('_', ' ', $deposit->status) }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-end">
                                <p class="text-xl font-black text-white leading-none">{{ \App\Services\CurrencyService::format($deposit->amount) }}</p>
                                <span class="text-[8px] font-black text-gray-600 uppercase tracking-widest italic">Awaiting Approval</span>
                            </div>
                            <div class="w-full h-1 bg-white/5 rounded-full overflow-hidden">
                                <div class="h-full bg-gold rounded-full" style="width: 70%"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Pending Acquisitions Console -->
            @if($pendingOrders->count() > 0)
            <div class="space-y-4">
                <div class="flex items-center space-x-3">
                    <span class="w-1.5 h-1.5 bg-luxury-bronze rounded-full animate-pulse"></span>
                    <span class="text-[9px] font-black text-gray-500 uppercase tracking-[0.4em]">Settlement Monitoring</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($pendingOrders as $order)
                    <div class="luxury-card p-6 flex flex-col justify-between border-white/5 bg-white/[0.02] group hover:border-gold/30 transition-all duration-500">
                        <div class="flex justify-between items-start mb-6">
                            <div class="space-y-1">
                                <p class="text-[8px] font-black text-gray-600 uppercase tracking-widest">Acquisition Ref</p>
                                <p class="text-xs font-black text-white italic">#{{ $order->id }}</p>
                            </div>
                            <div class="px-3 py-1 bg-black/40 rounded-full border border-white/10">
                                <p class="text-[7px] font-black text-gold uppercase tracking-widest">
                                    {{ str_replace('_', ' ', $order->status) }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-end">
                                <p class="text-xl font-black text-white leading-none">{{ \App\Services\CurrencyService::format($order->total_amount) }}</p>
                                @if($order->status === 'pending_payment')
                                <a href="{{ route('checkout.payment-details', $order->id) }}" class="text-[8px] font-black text-gold uppercase tracking-widest hover:text-white transition-colors">Complete Protocol →</a>
                                @else
                                <span class="text-[8px] font-black text-gray-600 uppercase tracking-widest italic">Verification in Progress</span>
                                @endif
                            </div>
                            <div class="w-full h-1 bg-white/5 rounded-full overflow-hidden">
                                <div class="h-full bg-gold/40 rounded-full" style="width: {{ $order->status === 'pending_payment' ? '30%' : '70%' }}"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12 items-center">
                <div class="space-y-8 md:space-y-10">
                    <div class="flex items-center space-x-6 md:space-x-12">
                        <!-- Gold Aperture -->
                        <div class="relative group flex-shrink-0">
                            <div class="w-28 h-28 md:w-40 md:h-40 rounded-full border-[8px] md:border-[12px] border-white/5 flex flex-col items-center justify-center text-center">
                                <span class="text-[7px] md:text-[9px] font-bold text-gray-500 uppercase tracking-widest">Gold</span>
                                <span class="text-xl md:text-3xl font-black text-gold">{{ round($goldPercent) }}%</span>
                            </div>
                            <!-- Simple SVG Ring for percentage -->
                            <svg class="absolute inset-0 w-28 h-28 md:w-40 md:h-40 -rotate-90">
                                <circle cx="56" cy="56" r="48" fill="none" stroke="url(#goldGradient)" stroke-width="8" stroke-dasharray="{{ ($goldPercent / 100) * 301 }} 301" stroke-linecap="round" class="transition-all duration-1000 ease-out md:hidden" />
                                <circle cx="80" cy="80" r="74" fill="none" stroke="url(#goldGradient)" stroke-width="12" stroke-dasharray="{{ ($goldPercent / 100) * 465 }} 465" stroke-linecap="round" class="transition-all duration-1000 ease-out hidden md:block" />
                                <defs>
                                    <linearGradient id="goldGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                        <stop offset="0%" style="stop-color:#d4af37" />
                                        <stop offset="100%" style="stop-color:#f97316" />
                                    </linearGradient>
                                </defs>
                            </svg>
                        </div>

                        <div class="space-y-2">
                            <h3 class="text-sm font-bold uppercase tracking-widest text-gray-400">Primary Reserve</h3>
                            <p class="text-3xl font-black tracking-tighter">{{ \App\Services\CurrencyService::format(collect($holdings)->where('metal', 'gold')->sum('current_value')) }}</p>
                            <div class="flex items-center space-x-2">
                                <span class="text-[10px] font-bold text-green-500 uppercase">+{{ number_format(collect($holdings)->where('metal', 'gold')->sum('profit_loss_percentage'), 1) }}% Performance</span>
                            </div>
                        </div>
                    </div>

                    <div class="h-[1px] w-full bg-gradient-to-r from-white/10 to-transparent"></div>

                    <div class="flex items-center space-x-6 md:space-x-12 opacity-80">
                        <!-- Silver Aperture -->
                        <div class="relative flex-shrink-0">
                            <div class="w-24 h-24 md:w-32 md:h-32 rounded-full border-[6px] md:border-[10px] border-white/5 flex flex-col items-center justify-center text-center">
                                <span class="text-[7px] md:text-[8px] font-bold text-gray-600 uppercase tracking-widest">Silver</span>
                                <span class="text-lg md:text-2xl font-black text-white">{{ round($silverPercent) }}%</span>
                            </div>
                            <svg class="absolute inset-0 w-24 h-24 md:w-32 md:h-32 -rotate-90">
                                <circle cx="48" cy="48" r="41" fill="none" stroke="#666" stroke-width="6" stroke-dasharray="{{ ($silverPercent / 100) * 257 }} 257" stroke-linecap="round" class="md:hidden" />
                                <circle cx="64" cy="64" r="59" fill="none" stroke="#666" stroke-width="10" stroke-dasharray="{{ ($silverPercent / 100) * 370 }} 370" stroke-linecap="round" class="hidden md:block" />
                            </svg>
                        </div>
                        
                        <div class="space-y-1">
                            <h3 class="text-xs font-bold uppercase tracking-widest text-gray-500">Stability Hedge</h3>
                            <p class="text-2xl font-black tracking-tighter">{{ \App\Services\CurrencyService::format(collect($holdings)->where('metal', 'silver')->sum('current_value')) }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 md:gap-6">
                    <button @click="showTradeModal = true" class="luxury-card p-6 md:p-10 flex flex-col justify-between aspect-square group hover:bg-gold transition-all duration-500 text-left">
                        <div class="w-8 h-8 md:w-12 md:h-12 rounded-full border border-white/10 flex items-center justify-center group-hover:border-black/20 group-hover:bg-black/5">
                            <svg class="w-4 h-4 md:w-6 md:h-6 text-gold group-hover:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        </div>
                        <div>
                            <p class="text-[8px] md:text-[10px] font-bold text-gray-500 uppercase tracking-widest group-hover:text-black/60 text-ellipsis overflow-hidden">Expand Vault</p>
                            <h4 class="text-sm md:text-xl font-black uppercase tracking-tighter group-hover:text-black">Trade</h4>
                        </div>
                    </button>

                    <a href="{{ route('transactions.index') }}" class="luxury-card p-6 md:p-10 flex flex-col justify-between aspect-square group hover:bg-white/10 transition-all duration-500 text-left border-white/5 bg-[#181818]">
                        <div class="w-8 h-8 md:w-12 md:h-12 rounded-full bg-white/5 flex items-center justify-center group-hover:bg-gold/10 group-hover:border-gold/20 transition-all">
                            <svg class="w-4 h-4 md:w-6 md:h-6 text-white group-hover:text-gold transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                        </div>
                        <div>
                            <p class="text-[8px] md:text-[10px] font-bold text-gray-500 uppercase tracking-widest group-hover:text-gold/60 transition-colors text-ellipsis overflow-hidden">View Data</p>
                            <h4 class="text-sm md:text-xl font-black uppercase tracking-tighter text-ellipsis overflow-hidden group-hover:text-white transition-colors">Analytics</h4>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Holdings Section -->
            <div class="space-y-10" id="holdings">
                <div class="flex justify-between items-center">
                    <h2 class="text-xs font-black uppercase tracking-[0.5em] text-gold/60">Custodial Assets</h2>
                    <a href="{{ route('holdings.index') }}" class="text-[10px] font-bold text-gray-500 hover:text-white transition-colors uppercase tracking-[0.3em] h-fit">View All Activity</a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse($holdings as $holding)
                    <div class="holding-card group">
                        <!-- Split Tone Header: High Radius + Charcoal -->
                        <div class="holding-card-header flex justify-between items-start">
                            <div class="space-y-1">
                                <span class="px-3 py-1 bg-white/5 border border-white/10 rounded-full text-[9px] font-bold text-gray-400 uppercase tracking-widest">
                                    LBMA Standard
                                </span>
                                <h3 class="text-xl font-bold uppercase tracking-tighter group-hover:text-gold transition-colors">{{ $holding['name'] }}</h3>
                            </div>
                            <div class="w-10 h-10 overflow-hidden bg-gradient-to-br {{ $holding['metal'] == 'gold' ? 'from-gold to-orange-500' : 'from-gray-400 to-gray-600' }} flex items-center justify-center text-[10px] text-black font-black border border-white/10 rounded-xl">
                                @if(isset($holding['image_path']) && $holding['image_path'])
                                    <img src="{{ Storage::url($holding['image_path']) }}" alt="{{ $holding['name'] }}" class="w-full h-full object-contain">
                                @else
                                    {{ strtoupper(substr($holding['metal'], 0, 2)) }}
                                @endif
                            </div>
                        </div>
                        
                        <!-- Main Body: Obsidian -->
                        <div class="holding-card-body space-y-8">
                            <div class="flex justify-between items-end">
                                <div>
                                    <p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest mb-1">Asset Quantity</p>
                                    <p class="text-2xl font-black">{{ number_format($holding['quantity'], 2) }}<span class="text-xs font-bold text-gray-500 ml-1">{{ strpos($holding['name'], 'Digital') !== false ? 'GRAMS' : 'UNITS' }}</span></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest mb-1">Current Value</p>
                                    <p class="text-2xl font-black text-white">{{ \App\Services\CurrencyService::format($holding['current_value']) }}</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-6 border-t border-white/5">
                                <div class="flex items-center space-x-2">
                                    <div class="w-1.5 h-1.5 rounded-full {{ $holding['profit_loss'] >= 0 ? 'bg-green-500' : 'bg-red-500' }}"></div>
                                    <span class="text-[10px] font-bold {{ $holding['profit_loss'] >= 0 ? 'text-green-500' : 'text-red-500' }} uppercase tracking-widest">
                                        {{ $holding['profit_loss'] >= 0 ? '+' : '' }}{{ \App\Services\CurrencyService::format($holding['profit_loss']) }} ({{ number_format($holding['profit_loss_percentage'], 1) }}%)
                                    </span>
                                </div>
                                <svg class="w-4 h-4 text-gray-700 hover:text-gold transition-colors cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-24 text-center luxury-card border-dashed">
                        <p class="text-gray-500 italic">No assets currently held in your private vault.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Trading Modal -->
        <div x-show="showTradeModal" 
             class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/90 backdrop-blur-xl"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             x-cloak>
            
            <div class="luxury-card w-full max-w-lg p-6 md:p-12 relative overflow-hidden" @click.away="showTradeModal = false">
                <!-- Modal Decor -->
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-gold/5 rounded-full blur-3xl"></div>
                
                <div class="relative space-y-8">
                    <div class="flex justify-between items-start">
                        <div class="space-y-2">
                            <h3 class="text-2xl font-black uppercase tracking-tighter">Asset Exchange</h3>
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.3em]">Institutional Trading Protocol</p>
                        </div>
                        <button @click="showTradeModal = false" class="text-gray-500 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <!-- Trade Options -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex bg-black p-1 rounded-xl border border-white/5">
                            <button @click="tradeType = 'buy'" 
                                    :class="tradeType === 'buy' ? 'bg-gold text-black' : 'text-gray-500 hover:text-white'"
                                    class="flex-1 py-3 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all">Buy</button>
                            <button @click="tradeType = 'sell'" 
                                    :class="tradeType === 'sell' ? 'bg-white text-black' : 'text-gray-500 hover:text-white'"
                                    class="flex-1 py-3 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all">Sell</button>
                        </div>
                        <div class="flex bg-black p-1 rounded-xl border border-white/5">
                            <button @click="tradeMetal = 'gold'" 
                                    :class="tradeMetal === 'gold' ? 'bg-gold/20 text-gold border border-gold/30' : 'text-gray-500 hover:text-white'"
                                    class="flex-1 py-3 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all">Gold</button>
                            <button @click="tradeMetal = 'silver'" 
                                    :class="tradeMetal === 'silver' ? 'bg-white/10 text-white border border-white/20' : 'text-gray-500 hover:text-white'"
                                    class="flex-1 py-3 text-[10px] font-black uppercase tracking-widest rounded-lg transition-all">Silver</button>
                        </div>
                    </div>

                    <!-- Input Area -->
                    <div class="space-y-4">
                        <div class="relative">
                            <input type="number" 
                                   x-model="tradeGrams"
                                   placeholder="0.00"
                                   class="w-full bg-black border border-white/10 rounded-2xl py-8 px-8 text-4xl font-black focus:border-gold focus:ring-0 transition-all placeholder-gray-800">
                            <span class="absolute right-8 top-1/2 -translate-y-1/2 text-xs font-black text-gray-500 tracking-widest">GRAMS</span>
                        </div>
                        
                        <div class="flex justify-between items-center px-2">
                            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Protocol Rate</span>
                            <span class="text-sm font-black text-gold" x-text="currencySymbol + Number(gramPrice).toFixed(2) + ' / g'"></span>
                        </div>
                    </div>

                    <!-- Total/CTA -->
                    <div class="pt-8 border-t border-white/5 space-y-6">
                        <div class="flex justify-between items-end">
                            <div class="space-y-1">
                                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Total Transaction Value</p>
                                <p class="text-3xl font-black tracking-tighter" x-text="currencySymbol + totalPrice"></p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest">Available Balance</p>
                                <p class="text-sm font-bold text-gray-400" x-text="currencySymbol + Number(tradeBalance).toLocaleString()"></p>
                            </div>
                        </div>

                        <!-- Messages -->
                        <div x-show="message" 
                             :class="status === 'success' ? 'bg-green-500/10 text-green-500 border-green-500/20' : 'bg-red-500/10 text-red-500 border-red-500/20'"
                             class="p-4 rounded-xl border text-[10px] font-bold uppercase tracking-widest text-center"
                             x-text="message"
                             x-transition></div>

                        <button @click="submitTrade()"
                                :disabled="processing || !tradeGrams || Number(tradeGrams) <= 0"
                                :class="processing ? 'opacity-50 cursor-not-allowed' : 'hover:scale-[1.02] active:scale-95'"
                                class="w-full bg-white text-black py-4 rounded-2xl font-black uppercase tracking-widest text-sm transition-all shadow-2xl shadow-white/5 flex items-center justify-center space-x-3">
                            <span x-show="!processing" x-text="tradeType === 'buy' ? 'Authorize Acquisition' : 'Execute Liquidation'"></span>
                            <span x-show="processing" class="flex items-center space-x-2">
                                <svg class="animate-spin h-4 w-4 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span>Processing...</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <x-bottom-nav-dock />
    </div>
</x-app-layout>
