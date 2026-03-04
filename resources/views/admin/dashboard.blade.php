<x-admin-layout>
    <div class="space-y-8">
        
        <!-- High-Density Metrics -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <div class="orbit-card p-6 space-y-4">
                <div class="flex justify-between items-start">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Custodians</p>
                    <div class="flex items-center gap-1">
                        <span class="text-[9px] font-black text-green-500 bg-green-50 px-2 py-0.5 rounded-md">+3 product</span>
                    </div>
                </div>
                <div>
                    <p class="orbit-metric-val text-gray-900">{{ $totalUsers }}</p>
                    <p class="text-[9px] font-bold text-gray-400 mt-1 uppercase tracking-widest">vs last month <span class="text-green-500">↑ 3%</span></p>
                </div>
            </div>

            <div class="orbit-card p-6 space-y-4">
                <div class="flex justify-between items-start">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Vault Liquidity</p>
                    <div class="flex items-center gap-1">
                        <span class="text-[9px] font-black text-green-500 bg-green-50 px-2 py-0.5 rounded-md">↑ 9%</span>
                    </div>
                </div>
                <div>
                    <p class="orbit-metric-val text-gray-900">{{ \App\Services\CurrencyService::formatNative($totalBalances) }}</p>
                    <p class="text-[9px] font-bold text-gray-400 mt-1 uppercase tracking-widest">vs last month <span class="text-green-500">↑ 9%</span></p>
                </div>
            </div>

            <div class="orbit-card p-6 space-y-4">
                <div class="flex justify-between items-start">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Pending Settlements</p>
                    <div class="flex items-center gap-1">
                        <span class="text-[9px] font-black text-orange-500 bg-orange-50 px-2 py-0.5 rounded-md">Action Required</span>
                    </div>
                </div>
                <div>
                    <p class="orbit-metric-val text-gray-900">{{ $pendingOrders }}</p>
                    <p class="text-[9px] font-bold text-gray-400 mt-1 uppercase tracking-widest">Outstanding verification</p>
                </div>
            </div>

            <div class="orbit-card p-6 space-y-4">
                <div class="flex justify-between items-start">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Avg. Portfolio</p>
                    <div class="flex items-center gap-1">
                        <span class="text-[9px] font-black text-green-500 bg-green-50 px-2 py-0.5 rounded-md">Optimal</span>
                    </div>
                </div>
                <div>
                    <p class="orbit-metric-val text-gray-900">{{ \App\Services\CurrencyService::formatNative($totalBalances / max(1, $totalUsers)) }}</p>
                    <p class="text-[9px] font-bold text-gray-400 mt-1 uppercase tracking-widest">vs last month <span class="text-green-500">↑ 5%</span></p>
                </div>
            </div>
        </div>

        <!-- Toolbar -->
        <div class="flex items-center justify-between pb-4">
            <div class="flex items-center gap-2">
                <button class="bg-white border border-gray-100 px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest text-gray-600 flex items-center gap-2 shadow-sm">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                    Table View
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <button class="bg-white border border-gray-100 px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest text-gray-600 flex items-center gap-2 shadow-sm">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Filter
                </button>
            </div>
            <div class="flex items-center gap-3">
                 <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Show Statistics</p>
                 <div class="w-8 h-4 bg-orange-500 rounded-full relative">
                    <div class="absolute right-0.5 top-0.5 w-3 h-3 bg-white rounded-full shadow-sm"></div>
                 </div>
            </div>
        </div>

        <!-- Master User Ledger Table -->
        <div class="orbit-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr>
                            <th class="orbit-table-th">Custodian Registry</th>
                            <th class="orbit-table-th">Settlement Balance</th>
                            <th class="orbit-table-th">Performance</th>
                            <th class="orbit-table-th">Registry Date</th>
                            <th class="orbit-table-th">Status</th>
                            <th class="orbit-table-th">Rating</th>
                            <th class="orbit-table-th">Protocols</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($recentUsers as $user)
                        <tr class="hover:bg-gray-50/50 transition-all group">
                            <td class="orbit-table-td">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center font-black text-[10px] text-gold border border-gray-100">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <span class="text-[11px] font-bold text-gray-900 group-hover:text-gold transition-colors">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="orbit-table-td">
                                <span class="text-[11px] font-black text-gray-900">{{ \App\Services\CurrencyService::formatNative($user->balance) }}</span>
                            </td>
                            <td class="orbit-table-td">
                                <span class="text-[10px] font-bold text-green-500">+12% flow</span>
                            </td>
                            <td class="orbit-table-td">
                                <span class="text-[10px] font-bold text-gray-500">{{ $user->created_at->format('M d, Y') }}</span>
                            </td>
                            <td class="orbit-table-td">
                                <span class="orbit-status-badge bg-blue-50 text-blue-600">Verified</span>
                            </td>
                            <td class="orbit-table-td">
                                <div class="flex items-center gap-1">
                                    <svg class="w-3 h-3 text-orange-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <span class="text-[10px] font-bold text-gray-900">5.0</span>
                                </div>
                            </td>
                            <td class="orbit-table-td">
                                <button class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-gold transition-colors">Edit Info</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="px-8 py-4 border-t border-gray-50 bg-gray-50/30 flex items-center justify-between">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Showing recent custodians</p>
                <div class="flex items-center gap-2">
                    <button class="w-7 h-7 rounded-lg border border-gray-200 bg-white flex items-center justify-center text-gray-400"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7m0 0l7-7"></path></svg></button>
                    <button class="w-7 h-7 rounded-lg bg-black text-white text-[10px] font-black">1</button>
                    <button class="w-7 h-7 rounded-lg border border-gray-200 bg-white text-[10px] font-black text-gray-600">2</button>
                    <button class="w-7 h-7 rounded-lg border border-gray-200 bg-white flex items-center justify-center text-gray-400"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg></button>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
