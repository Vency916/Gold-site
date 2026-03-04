<x-app-layout>
    <div class="pt-24 pb-32 bg-[#080808] min-h-screen text-white font-sans">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 space-y-16">
            
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row justify-between items-end gap-8">
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <span class="w-1.5 h-1.5 bg-gold rounded-full"></span>
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.4em]">Historical Ledger</span>
                    </div>
                    <h1 class="text-5xl font-black tracking-tighter uppercase leading-none">Custodial <span class="text-gold">Registry</span></h1>
                </div>

                <div class="flex items-center space-x-4 bg-white/5 rounded-full pl-6 pr-2 py-2 border border-white/5 backdrop-blur-md">
                    <div class="text-right">
                        <p class="text-[9px] font-bold text-gray-600 uppercase tracking-widest">Active Records</p>
                        <p class="text-xl font-black text-white">{{ count($holdings) }} Assets</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-gold flex items-center justify-center">
                        <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    </div>
                </div>
            </div>

            <!-- Holdings Full List -->
            <div class="space-y-10">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse($holdings as $holding)
                    <div class="luxury-card p-8 space-y-8 bg-[#121212] border-white/5 hover:border-gold/30 transition-all duration-500 relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gold/5 blur-[80px] rounded-full group-hover:bg-gold/10 transition-all"></div>
                        
                        <div class="flex justify-between items-start relative z-10">
                            <div class="space-y-1">
                                <span class="text-[10px] font-black text-gold uppercase tracking-[0.3em]">
                                    {{ $holding['created_at']->format('M d, Y') }}
                                </span>
                                <h3 class="text-xl font-bold uppercase tracking-tighter">{{ $holding['name'] }}</h3>
                            </div>
                            <div class="w-10 h-10 overflow-hidden bg-white/5 border border-white/10 flex items-center justify-center text-[10px] font-black rounded-xl">
                                @if(isset($holding['image_path']) && $holding['image_path'])
                                    <img src="{{ Storage::url($holding['image_path']) }}" alt="{{ $holding['name'] }}" class="w-full h-full object-contain">
                                @else
                                    {{ strtoupper(substr($holding['metal'], 0, 2)) }}
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6 relative z-10 pt-4 border-t border-white/5">
                            <div>
                                <p class="text-[8px] font-bold text-gray-500 uppercase tracking-widest mb-1">Asset Quantity</p>
                                <p class="text-xl font-black">{{ number_format($holding['quantity'], 2) }} <span class="text-[10px] text-gray-600">UNITS</span></p>
                            </div>
                            <div class="text-right">
                                <p class="text-[8px] font-bold text-gray-500 uppercase tracking-widest mb-1">Settlement Value</p>
                                <p class="text-xl font-black text-gold">{{ \App\Services\CurrencyService::format($holding['current_value']) }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-24 text-center luxury-card border-dashed">
                        <p class="text-gray-500 font-bold uppercase tracking-widest">No active physical records in registry.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Dashboard Return Link -->
            <div class="pt-8 flex justify-center">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 text-xs font-bold text-gray-500 hover:text-white transition-colors group">
                    <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    <span class="uppercase tracking-widest">Back to Command Center</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Navigation Dock Integration -->
    <x-bottom-nav-dock />
</x-app-layout>
