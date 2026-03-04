<x-app-layout>
    <div class="relative min-h-screen bg-[#080808] pb-32">
        <!-- Technical Backdrop -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-[10%] right-[5%] w-[40%] h-[40%] bg-gold/[0.03] blur-[120px] rounded-full"></div>
            <div class="absolute bottom-[10%] left-[5%] w-[30%] h-[30%] bg-white/[0.01] blur-[100px] rounded-full"></div>
        </div>

        <div class="relative pt-24 pb-12 px-6">
            <div class="max-w-4xl mx-auto space-y-12">
                <!-- Header -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 border-b border-white/5 pb-12">
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3">
                            <span class="w-1.5 h-1.5 bg-gold rounded-full animate-pulse"></span>
                            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.4em]">Settlement Desk</span>
                        </div>
                        <h1 class="text-4xl md:text-6xl font-black tracking-tighter uppercase leading-none text-white">
                            Funding <span class="text-gold italic">Settlement.</span>
                        </h1>
                    </div>
                    
                    <div class="luxury-card px-6 py-4 border-gold/20 bg-gold/5">
                        <span class="text-[8px] font-black text-gold uppercase tracking-[0.3em] mb-1 block text-right">Settlement Protocol Active</span>
                        <span class="text-3xl font-black text-white leading-none tracking-tighter">
                            {{ \App\Services\CurrencyService::formatNative($fundingData['amount']) }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                    <!-- Settlement Data -->
                    <div class="lg:col-span-2 space-y-8">
                        @if($fundingData['payment_method'] === 'bank_transfer')
                        <div class="luxury-card p-10 space-y-8 relative overflow-hidden">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 rounded-2xl bg-gold/10 flex items-center justify-center text-gold">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" /></svg>
                                </div>
                                <h2 class="text-xl font-black uppercase tracking-tighter">Institutional Wire Ledger</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-[11px] font-bold uppercase tracking-widest leading-loose">
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-gray-600 mb-1">Beneficiary Name</p>
                                        <p class="text-white border-b border-white/5 pb-2">{{ $settlementSettings['bank_beneficiary'] ?? 'Gold Reserve Institutional Ltd' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 mb-1">IBAN / Account Number</p>
                                        <p class="text-white border-b border-white/5 pb-2">{{ $settlementSettings['bank_iban'] ?? 'GB12 GOLD 0012 3456 7890 00' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 mb-1">SWIFT / BIC Code</p>
                                        <p class="text-white border-b border-white/5 pb-2">{{ $settlementSettings['bank_swift'] ?? 'GLDRSGB2L' }}</p>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-gray-600 mb-1">Receiving Institution</p>
                                        <p class="text-white border-b border-white/5 pb-2">{{ $settlementSettings['bank_name'] ?? 'Barclays Corporate Banking' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 mb-1">Settlement Reference</p>
                                        <p class="text-gold border-b border-gold/20 pb-2 font-black uppercase">FUND-{{ Auth::id() }}-INTENT</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 mb-1">Settlement Network</p>
                                        <p class="text-white border-b border-white/5 pb-2">{{ $settlementSettings['bank_network'] ?? 'CHAPS / SWIFT GPI' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-blue-500/5 border border-blue-500/10 p-6 rounded-2xl">
                                <p class="text-[9px] text-blue-400 font-bold uppercase tracking-widest leading-relaxed">
                                    <span class="mr-2">ℹ️</span> Please ensure the settlement reference is included in the wire instructions for rapid allocation. Funds typically clear within 2-4 institutional hours.
                                </p>
                            </div>
                        </div>
                        @else
                        <div class="luxury-card p-10 space-y-8 relative overflow-hidden">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 rounded-2xl bg-gold/10 flex items-center justify-center text-gold">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <h2 class="text-xl font-black uppercase tracking-tighter">Blockchain Vault Settlement</h2>
                            </div>

                            <div class="space-y-6">
                                <div class="bg-black/40 border border-white/5 p-8 rounded-3xl space-y-4">
                                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest text-center">Institutional {{ $fundingData['payment_network'] }} Vault Address</p>
                                    <div class="flex items-center space-x-4 bg-black/60 p-4 rounded-xl border border-white/10 group cursor-pointer hover:border-gold/50 transition-all" onclick="navigator.clipboard.writeText('{{ $fundingData['payment_address'] ?? 'Vault Address Error' }}')">
                                        <code class="flex-1 text-xs text-gold font-mono break-all text-center tracking-tighter">{{ $fundingData['payment_address'] ?? 'Vault Address Error' }}</code>
                                        <svg class="w-4 h-4 text-gray-600 group-hover:text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" /></svg>
                                    </div>
                                    <p class="text-[8px] text-gray-700 text-center uppercase tracking-[0.2em] font-bold">Click to copy vault destination</p>
                                </div>

                                <div class="bg-orange-500/5 border border-orange-500/10 p-6 rounded-2xl">
                                    <p class="text-[9px] text-orange-400 font-bold uppercase tracking-widest leading-relaxed">
                                        <span class="mr-2">⚠️</span> Verify the <span class="text-white">{{ $fundingData['payment_network'] }}</span> network is correctly selected. Institutional nodes only monitor authorized protocols.
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="bg-[#111111] border border-white/5 rounded-3xl p-8 space-y-4">
                                <h3 class="text-xs font-black uppercase tracking-widest text-gray-500">Asset Verification</h3>
                                <p class="text-[10px] font-bold text-gray-400 leading-relaxed uppercase tracking-tighter">
                                    Once settlements are received and normalized, your institutional balance will reflect the new capital injection in real-time.
                                </p>
                            </div>

                            <div class="bg-[#111111] border border-white/5 rounded-3xl p-8 space-y-4">
                                <h3 class="text-xs font-black uppercase tracking-widest text-gray-500">Institutional Support</h3>
                                <p class="text-[10px] font-bold text-gray-400 leading-relaxed uppercase tracking-tighter">
                                    Our concierge is available 24/7 to assist with capital settlement verification.
                                </p>
                                <button class="w-full py-4 bg-white/5 border border-white/10 rounded-xl text-[9px] font-black uppercase tracking-[0.3em] hover:bg-gold hover:text-black transition-all">Support Desk</button>
                            </div>
                        </div>
                    </div>

                    <!-- Receipt Upload Sidebar -->
                    <div class="space-y-8">
                        <div class="luxury-card p-10 space-y-8 sticky top-32">
                            <div class="space-y-4">
                                <h2 class="text-xl font-black uppercase tracking-tighter">Authorize <span class="text-gold">Receipt</span></h2>
                                <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest leading-loose">
                                    Upload your settlement confirmation to initiate capital allocation.
                                </p>
                            </div>

                            <form action="{{ route('capital.fund.submit-receipt') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                                @csrf
                                <div class="space-y-4">
                                    <label class="block">
                                        <div class="relative group cursor-pointer">
                                            <input type="file" name="receipt" class="hidden peer" onchange="document.getElementById('file-status').innerText = this.files[0].name" accept=".jpg,.jpeg,.png,.pdf">
                                            <div class="border-2 border-dashed border-white/10 rounded-3xl p-8 flex flex-col items-center justify-center space-y-4 group-hover:border-gold/50 transition-all bg-black/20">
                                                <div class="w-12 h-12 rounded-full bg-white/5 flex items-center justify-center text-gray-500 group-hover:text-gold transition-colors">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                                </div>
                                                <p id="file-status" class="text-[10px] font-black uppercase tracking-widest text-gray-600 text-center">Select Proof of Settlement</p>
                                            </div>
                                        </div>
                                    </label>
                                    @error('receipt')
                                        <p class="text-[9px] text-red-500 font-bold uppercase tracking-widest text-center">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit" class="w-full bg-white text-black py-4 rounded-xl font-black uppercase tracking-[0.2em] text-sm hover:bg-gold shadow-2xl transition-all">
                                    Finalize Protocol
                                </button>
                                
                                <p class="text-[10px] font-bold text-gray-700 text-center uppercase tracking-tighter">Submission initiates institutional verification.</p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <x-bottom-nav-dock />
    </div>
</x-app-layout>
