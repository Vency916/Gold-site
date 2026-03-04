<x-admin-layout>
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black tracking-tighter uppercase text-gray-900 leading-none">Acquisition <span class="text-gray-400">Queue</span></h1>
                <div class="flex items-center gap-2 mt-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-gold"></span>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Awaiting Verification & Vaulting</p>
                </div>
            </div>
        </div>

        <!-- Acquisition Tab Navigation -->
        <div class="flex items-center gap-6 overflow-x-auto pb-1">
            <button class="text-[10px] font-black uppercase tracking-widest text-gold border-b-2 border-gold pb-1 whitespace-nowrap">Pending Verification</button>
            <button class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-gray-600 transition-colors pb-1 whitespace-nowrap">Settled History</button>
            <button class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-gray-600 transition-colors pb-1 whitespace-nowrap">Rejected / Flagged</button>
        </div>

        <!-- Orders Table -->
        <div class="orbit-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr>
                            <th class="orbit-table-th">Order ID</th>
                            <th class="orbit-table-th">Custodian</th>
                            <th class="orbit-table-th">Settlement Val.</th>
                            <th class="orbit-table-th">Protocol</th>
                            <th class="orbit-table-th">Proof</th>
                            <th class="orbit-table-th">Registry Status</th>
                            <th class="orbit-table-th text-right">Verification Protocols</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($orders as $order)
                        <tr class="hover:bg-gray-50/50 transition-all group">
                            <td class="orbit-table-td">
                                <span class="text-[11px] font-black text-gray-900 font-mono tracking-tighter">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="orbit-table-td">
                                <div class="flex flex-col">
                                    <span class="text-[11px] font-bold text-gray-900">{{ $order->user->name }}</span>
                                    <span class="text-[9px] text-gray-400 font-bold uppercase mt-0.5 tracking-tighter">{{ $order->user->email }}</span>
                                </div>
                            </td>
                            <td class="orbit-table-td">
                                <span class="text-[11px] font-black text-gray-900">{{ \App\Services\CurrencyService::formatNative($order->total_amount) }}</span>
                            </td>
                            <td class="orbit-table-td">
                                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                                    {{ str_replace('_', ' ', $order->payment_method) }}
                                </span>
                            </td>
                            <td class="orbit-table-td">
                                @if($order->receipt_path)
                                <a href="{{ Storage::url($order->receipt_path) }}" target="_blank" class="flex items-center space-x-2 text-[9px] font-black text-gold uppercase tracking-[0.15em] hover:text-black transition-colors group/link">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    <span class="group-hover/link:underline underline-offset-4">Decrypt Receipt</span>
                                </a>
                                @else
                                <span class="text-[9px] font-bold text-gray-300 uppercase tracking-widest italic">N/A</span>
                                @endif
                            </td>
                            <td class="orbit-table-td">
                                <span class="orbit-status-badge {{ $order->status === 'approved' ? 'bg-green-50 text-green-600' : ($order->status === 'rejected' ? 'bg-red-50 text-red-600' : 'bg-orange-50 text-orange-600') }}">
                                    {{ str_replace('_', ' ', $order->status) }}
                                </span>
                            </td>
                            <td class="orbit-table-td text-right">
                                @if($order->status === 'pending_approval')
                                <div class="flex items-center justify-end gap-3">
                                    <form action="{{ route('admin.orders.reject', $order->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 transition-all border border-transparent hover:border-red-100 pointer-events-auto" title="Reject Receipt">
                                            <svg class="w-4 h-4 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.orders.approve', $order->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-green-400 hover:text-green-600 hover:bg-green-50 transition-all border border-transparent hover:border-green-100 pointer-events-auto" title="Approve & Vault">
                                            <svg class="w-4 h-4 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                        </button>
                                    </form>
                                </div>
                                @else
                                <button class="text-[10px] font-black uppercase tracking-widest text-gray-300 hover:text-gold transition-colors">View Audit</button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="orbit-table-td text-center py-16">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] italic">No pending settlements currently in queue.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
