<x-admin-layout>
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black tracking-tighter uppercase text-gray-900 leading-none">Custodian <span class="text-gray-400">Registry</span></h1>
                <div class="flex items-center gap-2 mt-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-gold"></span>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Global Liquidity Custodian Ledger</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <button class="orbit-btn-primary shadow-lg shadow-black/5">+ Register Custodian</button>
            </div>
        </div>

        <!-- Utility Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 orbit-card p-6">
            <div class="relative flex-1 max-w-md">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" /></svg>
                </div>
                <input type="text" placeholder="Intelligence Search Registry..." class="orbit-input w-full pl-10 h-10 font-bold">
            </div>
            <div class="flex items-center gap-4">
                <button class="bg-white border border-gray-100 px-4 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-600 hover:bg-gray-50 shadow-sm transition-all flex items-center gap-2">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                    Filter
                </button>
                <button class="bg-white border border-gray-100 px-4 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-600 hover:bg-gray-50 shadow-sm transition-all flex items-center gap-2">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                    Sort
                </button>
            </div>
        </div>

        <!-- Users Table -->
        <div class="orbit-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr>
                            <th class="orbit-table-th">Custodian Identity</th>
                            <th class="orbit-table-th">Registry Detail</th>
                            <th class="orbit-table-th">Available Liquid</th>
                            <th class="orbit-table-th">Managed Capital</th>
                            <th class="orbit-table-th">Registry Status</th>
                            <th class="orbit-table-th text-right">Protocols</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50/50 transition-all group">
                            <td class="orbit-table-td">
                                <div class="flex items-center gap-4">
                                    <div class="h-10 w-10 rounded-xl bg-gray-50 flex items-center justify-center border border-gray-100 font-black text-gold text-[10px] shadow-inner shadow-black/5 uppercase">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[11px] font-bold text-gray-900 group-hover:text-gold transition-colors">{{ $user->name }}</span>
                                        <span class="text-[9px] text-gray-400 font-bold uppercase mt-0.5 tracking-widest">{{ $user->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="orbit-table-td">
                                <span class="text-[11px] font-bold text-gray-600 tracking-tight">{{ $user->email }}</span>
                            </td>
                            <td class="orbit-table-td">
                                <div x-data="{ editing: false, balance: {{ $user->balance }} }">
                                    <div x-show="!editing" @click="editing = true" class="cursor-pointer hover:bg-gray-100 px-3 py-1.5 rounded-lg inline-block transition-all border border-transparent hover:border-gray-100">
                                        <span class="text-[11px] font-black text-gray-900">{{ \App\Services\CurrencyService::formatNative($user->balance) }}</span>
                                    </div>
                                    <form x-show="editing" @submit.prevent="fetch('{{ route('admin.users.update-balance', $user->id) }}', {
                                        method: 'POST',
                                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
                                        body: JSON.stringify({ balance: balance })
                                    }).then(() => { editing = false; window.location.reload(); })" class="flex items-center gap-2">
                                        <input type="number" x-model="balance" step="0.01" class="orbit-input w-28 py-1 px-2 font-bold !text-[10px]">
                                        <button type="submit" class="p-1 text-green-500 hover:scale-110 transition-transform"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg></button>
                                        <button type="button" @click="editing = false" class="p-1 text-red-500 hover:scale-110 transition-transform"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                                    </form>
                                </div>
                            </td>
                            <td class="orbit-table-td">
                                <span class="text-[10px] font-black text-gray-300 uppercase tracking-[0.2em] opacity-50">Locked</span>
                            </td>
                            <td class="orbit-table-td">
                                <span class="orbit-status-badge bg-blue-50 text-blue-600">Custodian</span>
                            </td>
                            <td class="orbit-table-td text-right">
                                <button class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-gold transition-colors">Protocol Desk</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
