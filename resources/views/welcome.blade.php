@php
    $goldPrice = App\Models\SpotPrice::where('metal', 'gold')->latest()->first();
    $silverPrice = App\Models\SpotPrice::where('metal', 'silver')->latest()->first();
    $products = App\Models\Product::physical()->take(8)->get();
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PREMIUM BULLION | Gold & Silver Marketplace</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased selection:bg-gold/30">
        <!-- Navigation -->
        @include('layouts.navigation')

        <!-- Flash Messages -->
        @if(session('success'))
        <div class="fixed top-24 right-6 z-[60] animate-bounce-in">
            <div class="luxury-card !bg-gold/10 border-gold/50 px-6 py-4 flex items-center space-x-3 backdrop-blur-md">
                <svg class="w-5 h-5 text-gold" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span class="text-sm font-bold text-gold tracking-wide">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        <!-- Live Price Ticker -->
        <div class="fixed top-20 md:top-[72px] w-full bg-black/60 backdrop-blur-2xl border-y border-white/5 py-4 z-40 overflow-hidden">
            <div class="flex items-center justify-center md:justify-start whitespace-nowrap md:animate-marquee">
                <!-- Main Prices -->
                <div class="flex items-center space-x-8 md:space-x-12 px-6">
                    <div class="flex items-center space-x-3">
                        <span class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">Gold</span>
                        <span class="text-sm font-serif text-gold">{{ \App\Services\CurrencyService::format($goldPrice->price ?? 0) }}</span>
                    </div>
                    <div class="flex items-center space-x-3 border-l border-white/10 pl-8 md:pl-12">
                        <span class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">Silver</span>
                        <span class="text-sm font-serif text-white">{{ \App\Services\CurrencyService::format($silverPrice->price ?? 0) }}</span>
                    </div>
                    <div class="hidden md:flex items-center space-x-3 border-l border-white/10 pl-12">
                        <span class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">Market Status</span>
                        <span class="text-[8px] px-2 py-0.5 bg-green-500/10 text-green-500 rounded-full border border-green-500/20 font-bold">ACTIVE</span>
                    </div>
                </div>
                
                <!-- Continuous Duplicate (Desktop Only) -->
                <div class="hidden md:flex items-center space-x-12 px-6">
                    <div class="flex items-center space-x-3">
                        <span class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">Gold</span>
                        <span class="text-sm font-serif text-gold">{{ \App\Services\CurrencyService::format($goldPrice->price ?? 0) }}</span>
                    </div>
                    <div class="flex items-center space-x-3 border-l border-white/10 pl-12">
                        <span class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">Silver</span>
                        <span class="text-sm font-serif text-white">{{ \App\Services\CurrencyService::format($silverPrice->price ?? 0) }}</span>
                    </div>
                    <div class="flex items-center space-x-3 border-l border-white/10 pl-12">
                        <span class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">Market Status</span>
                        <span class="text-[8px] px-2 py-0.5 bg-green-500/10 text-green-500 rounded-full border border-green-500/20 font-bold">ACTIVE</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hero Section -->
        <main class="relative">
            <!-- Fluid Hero Background -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute -top-[20%] -right-[10%] w-[80%] h-[120%] bg-gradient-to-br from-gold/10 via-transparent to-transparent blur-[120px] rounded-full opacity-40"></div>
                <div class="absolute top-[40%] -left-[10%] w-[60%] h-[80%] bg-gradient-to-tr from-luxury-bronze/5 via-transparent to-transparent blur-[100px] rounded-full opacity-20"></div>
            </div>

            <div class="relative pt-48 pb-24 px-6">
                <div class="max-w-6xl mx-auto text-center space-y-10">
                    <div class="space-y-4">
                        <span class="text-[10px] font-bold uppercase tracking-[0.5em] text-gold/60 animate-fade-in block">ESTABLISHED MCMXXIV</span>
                        <h1 class="text-6xl md:text-[8rem] font-bold leading-[0.9] tracking-tighter">
                            Preserving <br>
                            <span class="gold-gradient-text uppercase tracking-widest text-4xl md:text-6xl block mt-4">Intrinsic</span>
                            Value.
                        </h1>
                    </div>
                    
                    <div class="max-w-3xl mx-auto space-y-8">
                        <p class="text-lg text-gray-400 font-light leading-relaxed">
                            A highly curated marketplace for the world's most exquisite gold and silver specimens. 
                            Secure your future with the timeless stability of physical bullion.
                        </p>
                        
                        <div class="flex flex-col md:flex-row items-center justify-center gap-10 pt-4">
                            <a href="#marketplace" class="text-[10px] font-bold uppercase tracking-[0.3em] bg-white text-black px-12 py-5 hover:bg-gold transition-all duration-500 shadow-2xl shadow-white/5">
                                Explore Collection
                            </a>
                            <div class="flex items-center space-x-4 grayscale opacity-50">
                                <span class="h-[1px] w-12 bg-white/20"></span>
                                <span class="text-xs font-bold uppercase tracking-widest text-white/40">Circa 2024</span>
                                <span class="h-[1px] w-12 bg-white/20"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Marketplace Section -->
            <section id="marketplace" class="py-20 relative overflow-hidden">
                <!-- Subtle Backdrop Element -->
                <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-gold/[0.02] to-transparent pointer-events-none"></div>
                
                <div class="max-w-screen-2xl mx-auto px-4 md:px-12 relative z-10">
                    <!-- Redesigned Header: Centered & Sophisticated -->
                    <div class="max-w-4xl mx-auto text-center mb-20 space-y-8">
                        <div class="space-y-3">
                            <span class="text-[9px] font-bold uppercase tracking-[0.5em] text-gold/60 block">The Collection</span>
                            <h2 class="text-4xl md:text-7xl text-white font-bold leading-none tracking-tight">Curated <span class="gold-gradient-text uppercase">Selections.</span></h2>
                        </div>
                        
                        <div class="h-[1px] w-16 bg-gold/30 mx-auto"></div>
                        
                        <p class="text-gray-500 max-w-xl mx-auto text-xs md:text-sm leading-relaxed font-light">
                            Exquisite bullion specimens, selected for purity and historical significance.
                        </p>

                        <!-- Minimalist Filter Interface -->
                        <div class="flex flex-wrap justify-center gap-x-8 md:gap-x-12 gap-y-4 pt-6 border-t border-white/5">
                            <button class="text-[8px] font-bold uppercase tracking-[0.3em] text-gold border-b border-gold pb-1">All Assets</button>
                            <button class="text-[8px] font-bold uppercase tracking-[0.3em] text-gray-600 hover:text-white transition-all">Gold</button>
                            <button class="text-[8px] font-bold uppercase tracking-[0.3em] text-gray-600 hover:text-white transition-all">Silver</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-12">
                        @foreach($products as $product)
                        @php
                            $productPrice = $product->base_price;
                        @endphp
                        <div class="group luxury-card relative flex flex-col h-full transition-all duration-500 hover:-translate-y-1">
                            <!-- Product Image Area: Smaller & Compact -->
                            <div class="aspect-square relative overflow-hidden bg-gradient-to-br from-luxury-charcoal to-black flex items-center justify-center p-6 md:p-12">
                                <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/60 to-transparent"></div>
                                
                                <!-- Institutional Visual Protocol -->
                                @if($product->image_path)
                                    <img src="{{ Storage::url($product->image_path) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-full object-contain relative z-10">
                                @else
                                    <div class="text-6xl md:text-8xl font-black text-gold/10 uppercase relative z-10">
                                        {{ substr($product->metal, 0, 2) }}
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Product Info: Optimized for 2-column -->
                            <div class="p-4 md:p-8 space-y-4 flex-grow flex flex-col border-t border-white/5">
                                <div class="space-y-1">
                                    <h3 class="text-sm md:text-xl text-white group-hover:text-gold transition-colors font-bold tracking-tight leading-tight line-clamp-2 min-h-[2.5rem] md:min-h-0">{{ $product->name }}</h3>
                                    <div class="flex justify-between items-center text-[8px] md:text-[10px] text-gray-500 uppercase tracking-widest">
                                        <span>{{ $product->weight }}g</span>
                                        <span class="text-gold font-bold">{{ $product->metal }}</span>
                                    </div>
                                </div>
                                
                                <div class="space-y-4 pt-3 border-t border-white/5">
                                    <div class="flex flex-col md:flex-row justify-between md:items-baseline gap-1">
                                        <span class="text-[7px] md:text-[10px] font-bold text-gray-600 uppercase tracking-widest">Market Value</span>
                                        <span class="text-sm md:text-2xl font-bold text-white">{{ \App\Services\CurrencyService::format($productPrice) }}</span>
                                    </div>

                                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full bg-white text-black text-[8px] md:text-[10px] font-bold py-3 md:py-4 uppercase tracking-[0.3em] hover:bg-gold transition-all duration-300 active:scale-95">
                                            Purchase
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <!-- Insight Section -->
            <section class="py-48 bg-luxury-cream text-black relative overflow-hidden">
                 <!-- Aesthetic Background Text -->
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-[20vw] font-black text-black/[0.03] select-none pointer-events-none leading-none whitespace-nowrap uppercase tracking-tighter">
                    Heritage
                </div>

                <div class="max-w-7xl mx-auto px-6 flex flex-col items-center text-center space-y-16 relative z-10">
                    <div class="space-y-6">
                        <span class="text-[10px] font-bold uppercase tracking-[0.5em] text-luxury-bronze/60">Insight & Commentary</span>
                        <h2 class="text-6xl md:text-[8rem] font-black leading-none tracking-tighter uppercase">The Wealth <br> <span class="text-luxury-bronze">Periodical.</span></h2>
                    </div>
                    
                    <div class="grid md:grid-cols-3 gap-16 w-full">
                        <div class="space-y-8 text-left group cursor-pointer">
                            <div class="h-[1px] w-full bg-black/10"></div>
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Issue 01 / Winter 24</span>
                            <h3 class="text-3xl font-bold group-hover:translate-x-4 transition-transform duration-500">The Case for Physical Stability in a Digital Age.</h3>
                            <p class="text-sm text-gray-600 leading-relaxed font-light">An analysis of gold's performance amidst the rise of decentralized finance.</p>
                        </div>
                        <div class="space-y-8 text-left group cursor-pointer">
                            <div class="h-[1px] w-full bg-black/10"></div>
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Issue 02 / Spring 24</span>
                            <h3 class="text-3xl font-serif group-hover:translate-x-4 transition-transform duration-500 italic">Historical Precedence of the Royal Mint.</h3>
                            <p class="text-sm text-gray-600 leading-relaxed font-light">From sovereigns to britannias: a history of British bullion supremacy.</p>
                        </div>
                        <div class="space-y-8 text-left group cursor-pointer">
                            <div class="h-[1px] w-full bg-black/10"></div>
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Issue 03 / Summer 24</span>
                            <h3 class="text-3xl font-serif group-hover:translate-x-4 transition-transform duration-500 italic">Strategic Allocation: Diversifying Portfolios.</h3>
                            <p class="text-sm text-gray-600 leading-relaxed font-light">Modern strategies for traditional wealth management and protection.</p>
                        </div>
                    </div>

                    <a href="#" class="inline-block border-b-2 border-black pb-2 text-[10px] font-bold uppercase tracking-[0.4em] pt-8 hover:text-luxury-bronze hover:border-luxury-bronze transition-all">View All Entries</a>
                </div>
            </section>
        </main>

        <footer class="bg-black py-32 border-t border-white/5">
            <div class="max-w-screen-2xl mx-auto px-12 grid grid-cols-1 md:grid-cols-4 gap-24">
                <div class="space-y-12">
                     <span class="text-3xl font-serif tracking-tighter text-white">Golden <span class="italic text-gold">Vault</span></span>
                     <p class="text-gray-500 text-xs leading-loose font-light uppercase tracking-widest">
                        The premier marketplace for physical precious metal investment. <br>
                        Reg. 12457890 • London, UK
                     </p>
                </div>
                
                <div class="space-y-8">
                    <h5 class="text-[10px] font-bold uppercase tracking-[0.3em] text-white">Collections</h5>
                    <ul class="space-y-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                        <li><a href="#" class="hover:text-gold transition-colors">Gold Bullion</a></li>
                        <li><a href="#" class="hover:text-gold transition-colors">Silver Specimen</a></li>
                        <li><a href="#" class="hover:text-gold transition-colors">Gift Cards</a></li>
                    </ul>
                </div>

                <div class="space-y-8">
                    <h5 class="text-[10px] font-bold uppercase tracking-[0.3em] text-white">Heritage</h5>
                    <ul class="space-y-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                        <li><a href="#" class="hover:text-gold transition-colors">Our History</a></li>
                        <li><a href="#" class="hover:text-gold transition-colors">The Vault</a></li>
                        <li><a href="#" class="hover:text-gold transition-colors">Contact</a></li>
                    </ul>
                </div>

                <div class="space-y-8">
                    <h5 class="text-[10px] font-bold uppercase tracking-[0.3em] text-white">Newsletter</h5>
                    <div class="relative">
                        <input type="email" placeholder="ADDRESS@EMAIL.COM" class="w-full bg-transparent border-b border-white/10 py-4 text-[10px] focus:outline-none focus:border-gold transition-colors placeholder:text-gray-800">
                        <button class="absolute right-0 bottom-4 text-gold text-[10px] font-bold uppercase tracking-widest">Join</button>
                    </div>
                </div>
            </div>
            
            <div class="max-w-screen-2xl mx-auto px-12 mt-32 pt-12 border-t border-white/[0.03] flex justify-between items-center text-[8px] font-bold text-gray-700 uppercase tracking-[0.5em]">
                <span>© 2024 Golden Vault Ltd</span>
                <span>Experience Design by Antigravity</span>
            </div>
        </footer>
    </body>
</html>
