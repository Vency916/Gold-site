<x-admin-layout>
    <div class="space-y-8 max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between font-sans">
            <div>
                <h1 class="text-3xl font-black tracking-tighter uppercase text-gray-900 leading-none">Settlement <span class="text-gray-400">Protocols</span></h1>
                <div class="flex items-center gap-2 mt-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-gold"></span>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Institutional Logistics Gateway</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.settings.payment-methods.update') }}" method="POST" class="space-y-8">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Bank Transfer Configuration -->
                <div class="orbit-card overflow-hidden">
                    <div class="p-8 space-y-6">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-6">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 rounded-xl bg-gold/10 flex items-center justify-center text-gold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                                </div>
                                <h2 class="text-xs font-black uppercase tracking-widest text-gray-900">Institutional Wire Ledger</h2>
                            </div>
                            <!-- Toggle -->
                            <div class="flex items-center space-x-3">
                                <span class="text-[9px] font-black uppercase tracking-widest text-gray-400">Method Access</span>
                                <input type="hidden" name="bank_transfer_enabled" value="0">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="bank_transfer_enabled" value="1" class="sr-only peer" {{ ($bankSettings['bank_transfer_enabled']->value ?? '1') == '1' ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gold"></div>
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">Beneficiary Name</label>
                                <input type="text" name="bank_beneficiary" value="{{ $bankSettings['bank_beneficiary']->value ?? '' }}" class="orbit-input w-full px-4 py-3 text-xs font-bold">
                            </div>

                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">IBAN / Account Number</label>
                                <input type="text" name="bank_iban" value="{{ $bankSettings['bank_iban']->value ?? '' }}" class="orbit-input w-full px-4 py-3 text-xs font-mono font-bold">
                            </div>

                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">SWIFT / BIC Code</label>
                                <input type="text" name="bank_swift" value="{{ $bankSettings['bank_swift']->value ?? '' }}" class="orbit-input w-full px-4 py-3 text-xs font-mono font-bold">
                            </div>

                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">Receiving Institution</label>
                                <input type="text" name="bank_name" value="{{ $bankSettings['bank_name']->value ?? '' }}" class="orbit-input w-full px-4 py-3 text-xs font-bold">
                            </div>

                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">Settlement Network</label>
                                <input type="text" name="bank_network" value="{{ $bankSettings['bank_network']->value ?? '' }}" class="orbit-input w-full px-4 py-3 text-xs font-bold">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cash Mailing Configuration -->
                <div class="orbit-card overflow-hidden">
                    <div class="p-8 space-y-6">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-6">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 rounded-xl bg-gold/10 flex items-center justify-center text-gold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </div>
                                <h2 class="text-xs font-black uppercase tracking-widest text-gray-900">Physical Value Logistics</h2>
                            </div>
                            <!-- Toggle -->
                            <div class="flex items-center space-x-3">
                                <span class="text-[9px] font-black uppercase tracking-widest text-gray-400">Method Access</span>
                                <input type="hidden" name="cash_mailing_enabled" value="0">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="cash_mailing_enabled" value="1" class="sr-only peer" {{ ($mailingSettings['cash_mailing_enabled']->value ?? '1') == '1' ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gold"></div>
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">Recipient Name</label>
                                <input type="text" name="mailing_name" value="{{ $mailingSettings['mailing_name']->value ?? '' }}" class="orbit-input w-full px-4 py-3 text-xs font-bold">
                            </div>

                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">Address Line 1</label>
                                <input type="text" name="mailing_address_1" value="{{ $mailingSettings['mailing_address_1']->value ?? '' }}" class="orbit-input w-full px-4 py-3 text-xs font-bold">
                            </div>

                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">Address Line 2 (Optional)</label>
                                <input type="text" name="mailing_address_2" value="{{ $mailingSettings['mailing_address_2']->value ?? '' }}" class="orbit-input w-full px-4 py-3 text-xs font-bold">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">City / Region</label>
                                    <input type="text" name="mailing_city" value="{{ $mailingSettings['mailing_city']->value ?? '' }}" class="orbit-input w-full px-4 py-3 text-xs font-bold">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">Postcode</label>
                                    <input type="text" name="mailing_postcode" value="{{ $mailingSettings['mailing_postcode']->value ?? '' }}" class="orbit-input w-full px-4 py-3 text-xs font-mono font-bold">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="orbit-btn px-12 py-4">
                    Deploy Protocol Updates
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
