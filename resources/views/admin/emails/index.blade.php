<x-admin-layout>
    <div class="space-y-8 max-w-6xl mx-auto" x-data="{ recipientType: 'all' }">
        <!-- Header -->
        <div class="flex items-center justify-between font-sans">
            <div>
                <h1 class="text-3xl font-black tracking-tighter uppercase text-gray-900 leading-none">Email <span class="text-gray-400">Center</span></h1>
                <div class="flex items-center gap-2 mt-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Institutional Communication Hub</p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-green-50 border border-green-100 rounded-2xl flex items-center gap-3">
                <span class="text-green-500 text-lg">✓</span>
                <p class="text-xs font-bold text-green-700 uppercase tracking-widest">{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Compose Section -->
            <div class="lg:col-span-2 space-y-6">
                <div class="orbit-card p-8 space-y-8">
                    <div class="flex items-center space-x-4 border-b border-gray-100 pb-6">
                        <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </div>
                        <h2 class="text-xs font-black uppercase tracking-widest text-gray-900">Compose New Dispatch</h2>
                    </div>

                    <form action="{{ route('admin.emails.send') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div class="space-y-4">
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">Recipient Targeting</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="recipient_type" value="all" x-model="recipientType" class="sr-only peer">
                                    <div class="p-4 border border-gray-100 rounded-2xl bg-gray-50/50 text-center transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 group-hover:bg-white">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 peer-checked:text-blue-600">Broadcast (All Users)</p>
                                    </div>
                                </label>
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="recipient_type" value="specific" x-model="recipientType" class="sr-only peer">
                                    <div class="p-4 border border-gray-100 rounded-2xl bg-gray-50/50 text-center transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 group-hover:bg-white">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 peer-checked:text-blue-600">Targeted Selection</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- User Selection (Hidden if Broadcast) -->
                        <div class="space-y-2" x-show="recipientType === 'specific'" x-cloak x-collapse>
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">Select Recipients</label>
                            <div class="orbit-card bg-gray-50/50 p-4 max-h-48 overflow-y-auto space-y-2">
                                @foreach($users as $user)
                                    <label class="flex items-center space-x-3 p-2 hover:bg-white rounded-xl cursor-pointer transition-all">
                                        <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="rounded border-gray-300 text-blue-500 focus:ring-blue-500">
                                        <div class="flex flex-col">
                                            <span class="text-[11px] font-bold text-gray-800">{{ $user->name }}</span>
                                            <span class="text-[9px] text-gray-400 font-mono">{{ $user->email }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">Subject Header</label>
                            <input type="text" name="subject" required placeholder="Institutional Update: Protocol Enhancement" class="orbit-input w-full px-4 py-3 text-xs font-bold">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">Message Content</label>
                            <textarea name="message" rows="8" required placeholder="Enter your institutional message here..." class="orbit-input w-full px-4 py-3 text-xs font-medium leading-loose resize-none"></textarea>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="orbit-btn w-full py-4 text-[10px]">
                                Authorize & Dispatch Courier
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- History Section -->
            <div class="space-y-6">
                <div class="orbit-card p-6 space-y-6">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                        <h2 class="text-[10px] font-black uppercase tracking-widest text-gray-900">Dispatch Registry</h2>
                        <span class="text-[9px] font-bold text-gray-400">{{ $history->total() }} Logged</span>
                    </div>

                    <div class="space-y-4">
                        @forelse($history as $log)
                            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 space-y-2">
                                <div class="flex justify-between items-start">
                                    <p class="text-[10px] font-black text-gray-800 line-clamp-1">{{ $log->subject }}</p>
                                    <span class="text-[8px] font-bold text-gray-400 whitespace-nowrap">{{ $log->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-blue-400"></div>
                                    <p class="text-[9px] font-bold text-gray-500 line-clamp-1">To: {{ $log->recipient_email }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest">No dispatch history</p>
                            </div>
                        @endforelse
                    </div>

                    @if($history->hasPages())
                        <div class="pt-4 border-t border-gray-100">
                            {{ $history->links() }}
                        </div>
                    @endif
                </div>

                <div class="p-6 bg-blue-500/5 border border-blue-500/10 rounded-2xl">
                    <div class="flex items-start gap-3">
                        <span class="text-blue-500">ℹ️</span>
                        <div class="space-y-1">
                            <p class="text-[9px] font-black text-blue-700 uppercase tracking-widest">Protocol Tip</p>
                            <p class="text-[9px] font-medium text-blue-600 leading-relaxed uppercase tracking-tighter">
                                Broadcast emails are queued for processing. High volume dispatches may take several minutes to clear.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
