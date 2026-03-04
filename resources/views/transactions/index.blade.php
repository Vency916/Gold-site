<x-app-layout>
    <div class="relative min-h-screen bg-[#080808] pb-32">
        <!-- Technical Backdrop -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-[10%] right-[5%] w-[40%] h-[40%] bg-gold/[0.03] blur-[120px] rounded-full"></div>
            <div class="absolute bottom-[10%] left-[5%] w-[30%] h-[30%] bg-white/[0.01] blur-[100px] rounded-full"></div>
        </div>

        <div class="relative pt-24 pb-12 px-6">
            <div class="max-w-6xl mx-auto space-y-12">
                <!-- Header -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 border-b border-white/5 pb-12">
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3">
                            <span class="w-1.5 h-1.5 bg-gold rounded-full animate-pulse"></span>
                            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.4em]">Audit Registry</span>
                        </div>
                        <h1 class="text-4xl md:text-6xl font-black tracking-tighter uppercase leading-none text-white">
                            Transaction <span class="text-gold italic">History.</span>
                        </h1>
                    </div>
                </div>

                <!-- Transaction Ledger -->
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xs font-black uppercase tracking-[0.5em] text-gold/60">Custodial Audit Trail</h2>
                        <span class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">{{ $transactions->count() }} Total Actions</span>
                    </div>

                    <div class="luxury-card overflow-hidden border-white/5 bg-white/[0.01]">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="border-b border-white/5 bg-white/[0.02]">
                                        <th class="px-8 py-5 text-[9px] font-black text-gray-500 uppercase tracking-[0.2em]">Protocol / Ref</th>
                                        <th class="px-8 py-5 text-[9px] font-black text-gray-500 uppercase tracking-[0.2em]">Action Type</th>
                                        <th class="px-8 py-5 text-[9px] font-black text-gray-500 uppercase tracking-[0.2em]">Settlement Val.</th>
                                        <th class="px-8 py-5 text-[9px] font-black text-gray-500 uppercase tracking-[0.2em]">Status</th>
                                        <th class="px-8 py-5 text-[9px] font-black text-gray-500 uppercase tracking-[0.2em]">Date</th>
                                        <th class="px-8 py-5 text-[10px] text-right"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @forelse($transactions as $tx)
                                    <tr class="group hover:bg-white/[0.02] transition-all duration-300">
                                        <td class="px-8 py-6">
                                            <div class="flex flex-col">
                                                <span class="text-xs font-black text-white tracking-widest">{{ $tx['reference'] }}</span>
                                                <span class="text-[9px] font-bold text-gray-600 uppercase mt-1">{{ $tx['method'] }}</span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-2 h-2 rounded-full {{ $tx['is_revenue'] ? 'bg-gold' : 'bg-gray-400' }}"></div>
                                                <span class="text-[10px] font-black text-white uppercase tracking-widest">{{ $tx['type'] }}</span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-black {{ $tx['is_revenue'] ? 'text-gold' : 'text-white' }}">
                                                    {{ $tx['is_revenue'] ? '+' : '-' }}{{ \App\Services\CurrencyService::format($tx['amount']) }}
                                                </span>
                                                <span class="text-[8px] font-bold text-gray-600 uppercase mt-1">{{ $tx['meta'] }}</span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6">
                                            <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest border 
                                                {{ $tx['status'] === 'approved' ? 'bg-green-500/10 text-green-400 border-green-500/20' : 
                                                   ($tx['status'] === 'rejected' ? 'bg-red-500/10 text-red-400 border-red-500/20' : 'bg-orange-500/10 text-orange-400 border-orange-500/20') }}">
                                                {{ str_replace('_', ' ', $tx['status'] === 'pending_payment' ? 'Intent Active' : $tx['status']) }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-6">
                                            <span class="text-[10px] font-bold text-gray-500 uppercase">{{ $tx['date']->format('d M Y') }}</span>
                                        </td>
                                        <td class="px-8 py-6 text-right">
                                            <button class="text-[8px] font-black text-gray-700 uppercase tracking-widest hover:text-gold transition-colors">Audit File →</button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="px-8 py-20 text-center">
                                            <p class="text-[9px] font-black text-gray-600 uppercase tracking-[0.3em] italic">No custodial history registered in the vault.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Footnote -->
                    <div class="flex items-center justify-center space-x-4 pt-12">
                        <div class="h-[1px] w-12 bg-white/5"></div>
                        <p class="text-[8px] font-black text-gray-700 uppercase tracking-[0.5em]">Institutional Transparency Protocol</p>
                        <div class="h-[1px] w-12 bg-white/5"></div>
                    </div>
                </div>
            </div>
        </div>

        <x-bottom-nav-dock />
    </div>
</x-app-layout>
