<x-app-layout>
    <div class="relative min-h-screen bg-luxury-black pb-32">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-[10%] -right-[10%] w-[60%] h-[60%] bg-gold/[0.03] blur-[120px] rounded-full"></div>
        </div>

        <div class="relative pt-24 pb-8 px-6">
            <div class="max-w-3xl mx-auto">
                <div class="space-y-4 text-center">
                    <span class="text-[10px] font-bold uppercase tracking-[0.5em] text-gold/60 block">Acquisition Step 01</span>
                    <h1 class="text-4xl md:text-5xl font-black tracking-tighter uppercase leading-none text-white">
                        Delivery <span class="gold-gradient-text">Protocol.</span>
                    </h1>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-4">Provide custodial delivery and contact credentials</p>
                </div>
            </div>
        </div>

        <div class="relative max-w-3xl mx-auto px-6 lg:px-8">
            <div class="luxury-card p-8 md:p-12">
                <form action="{{ route('checkout.postDetails') }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Full Identity</label>
                                <input type="text" name="shipping_name" value="{{ old('shipping_name', $checkoutData['shipping_name'] ?? '') }}" required
                                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-4 text-white focus:border-gold/50 focus:ring-0 transition-all font-sans text-sm"
                                    placeholder="Enter full legal name">
                                @error('shipping_name') <p class="text-red-500 text-[10px] font-bold uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Contact Channel</label>
                                <input type="text" name="shipping_phone" value="{{ old('shipping_phone', $checkoutData['shipping_phone'] ?? '') }}" required
                                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-4 text-white focus:border-gold/50 focus:ring-0 transition-all font-sans text-sm"
                                    placeholder="+44 000 000 0000">
                                @error('shipping_phone') <p class="text-red-500 text-[10px] font-bold uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Custodial Address</label>
                            <input type="text" name="shipping_address" value="{{ old('shipping_address', $checkoutData['shipping_address'] ?? '') }}" required
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-4 text-white focus:border-gold/50 focus:ring-0 transition-all font-sans text-sm"
                                placeholder="Street address, building, suite">
                            @error('shipping_address') <p class="text-red-500 text-[10px] font-bold uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Jurisdiction / City</label>
                                <input type="text" name="shipping_city" value="{{ old('shipping_city', $checkoutData['shipping_city'] ?? '') }}" required
                                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-4 text-white focus:border-gold/50 focus:ring-0 transition-all font-sans text-sm"
                                    placeholder="City name">
                                @error('shipping_city') <p class="text-red-500 text-[10px] font-bold uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Postal Registry Code</label>
                                <input type="text" name="shipping_postcode" value="{{ old('shipping_postcode', $checkoutData['shipping_postcode'] ?? '') }}" required
                                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-4 text-white focus:border-gold/50 focus:ring-0 transition-all font-sans text-sm"
                                    placeholder="Postcode / ZIP">
                                @error('shipping_postcode') <p class="text-red-500 text-[10px] font-bold uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="pt-8 border-t border-white/5 flex flex-col md:flex-row gap-4">
                        <a href="{{ route('cart.index') }}" class="flex-1 text-center bg-white/5 text-gray-400 text-[10px] font-bold py-5 uppercase tracking-[0.3em] hover:bg-white/10 transition-all duration-500 rounded-xl">
                            Return to Registry
                        </a>
                        <button type="submit" class="flex-[2] bg-white text-black text-[10px] font-black py-5 uppercase tracking-[0.4em] hover:bg-gold transition-all duration-500 active:scale-95 shadow-xl shadow-white/5 rounded-xl">
                            Initialize Payment Step
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
