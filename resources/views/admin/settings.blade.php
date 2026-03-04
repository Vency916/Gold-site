<x-admin-layout>
    <div class="space-y-8 max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black tracking-tighter uppercase text-gray-900 leading-none">Protocol <span class="text-gray-400">Settings</span></h1>
                <div class="flex items-center gap-2 mt-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-gold"></span>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Global Master Configuration</p>
                </div>
            </div>
        </div>

        <div class="orbit-card overflow-hidden">
            <div class="p-8">
                <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <h3 class="text-xs font-black uppercase tracking-widest text-gray-900 mb-6 border-b border-gray-100 pb-4">Acquisition Incentives</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                            <div class="space-y-2 col-span-1 md:col-span-2">
                                <label for="site_name" class="block text-[10px] font-black uppercase tracking-widest text-gray-700">
                                    Golden Vault Site Name
                                </label>
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-4">
                                    {{ $settings['siteName']->description }}
                                </p>
                                <input type="text" name="site_name" id="site_name" required
                                    class="orbit-input w-full pl-4 pr-4 {{ $errors->has('site_name') ? 'border-red-500' : '' }}"
                                    value="{{ old('site_name', $settings['siteName']->value) }}">
                                @error('site_name')
                                    <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label for="cash_mailing_discount" class="block text-[10px] font-black uppercase tracking-widest text-gray-700">
                                    Cash Settlement Discount (%)
                                </label>
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-4">
                                    {{ $settings['cashMailingDiscount']->description }}
                                </p>
                                <div class="relative">
                                    <input type="number" step="0.01" min="0" max="100" name="cash_mailing_discount" id="cash_mailing_discount" required
                                        class="orbit-input w-full pl-4 pr-10 {{ $errors->has('cash_mailing_discount') ? 'border-red-500' : '' }}"
                                        value="{{ old('cash_mailing_discount', $settings['cashMailingDiscount']->value) }}">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                        <span class="text-gray-400 font-black text-sm">%</span>
                                    </div>
                                </div>
                                @error('cash_mailing_discount')
                                    <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Metal Price Oracle Configuration -->
                        <div class="pt-12 mt-12 border-t border-gray-100">
                            <h3 class="text-xs font-black uppercase tracking-widest text-gray-900 mb-6 border-b border-gray-100 pb-4">Market Intelligence (GoldAPI.io)</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-1">
                                    <label class="text-[9px] font-black uppercase tracking-widest text-gray-400">API Key (Oracle Node)</label>
                                    <input type="text" name="metal_api_key" value="{{ $settings['metalSettings']['metal_api_key']->value ?? '' }}" class="orbit-input w-full py-2 px-3 text-xs" placeholder="e.g. goldapi-xxxxxxxxxxxxxx">
                                    <p class="text-[8px] font-medium text-gray-400 mt-1">Visit <a href="https://www.goldapi.io/" target="_blank" class="text-gold hover:underline">GoldAPI.io</a> to generate your authentication token.</p>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[9px] font-black uppercase tracking-widest text-gray-400">USD to Local Conversion Rate</label>
                                    <input type="number" step="0.0001" name="metal_conversion_rate" value="{{ $settings['metalSettings']['metal_conversion_rate']->value ?? '0.78' }}" class="orbit-input w-full py-2 px-3 text-xs" placeholder="0.78">
                                    <p class="text-[8px] font-medium text-gray-400 mt-1">multiplier to convert USD spot prices to GBP or other local currencies.</p>
                                </div>
                            </div>
                        </div>

                        <!-- SMTP Configuration moved here to avoid nested forms -->
                        <div class="pt-12 mt-12 border-t border-gray-100">
                            <h3 class="text-xs font-black uppercase tracking-widest text-gray-900 mb-6 border-b border-gray-100 pb-4">Communication Infrastructure (SMTP)</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-1">
                                    <label class="text-[9px] font-black uppercase tracking-widest text-gray-400">SMTP Host</label>
                                    <input type="text" name="mail_host" value="{{ $settings['smtpSettings']['mail_host']->value ?? '' }}" class="orbit-input w-full py-2 px-3 text-xs" placeholder="smtp.mailtrap.io">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[9px] font-black uppercase tracking-widest text-gray-400">SMTP Port</label>
                                    <input type="text" name="mail_port" value="{{ $settings['smtpSettings']['mail_port']->value ?? '' }}" class="orbit-input w-full py-2 px-3 text-xs" placeholder="587">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[9px] font-black uppercase tracking-widest text-gray-400">Encryption</label>
                                    <select name="mail_encryption" class="orbit-input w-full py-2 px-3 text-xs">
                                        <option value="tls" {{ ($settings['smtpSettings']['mail_encryption']->value ?? '') == 'tls' ? 'selected' : '' }}>TLS</option>
                                        <option value="ssl" {{ ($settings['smtpSettings']['mail_encryption']->value ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                        <option value="none" {{ ($settings['smtpSettings']['mail_encryption']->value ?? '') == 'none' ? 'selected' : '' }}>None</option>
                                    </select>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[9px] font-black uppercase tracking-widest text-gray-400">Username</label>
                                    <input type="text" name="mail_username" value="{{ $settings['smtpSettings']['mail_username']->value ?? '' }}" class="orbit-input w-full py-2 px-3 text-xs">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[9px] font-black uppercase tracking-widest text-gray-400">Password</label>
                                    <input type="password" name="mail_password" value="{{ $settings['smtpSettings']['mail_password']->value ?? '' }}" class="orbit-input w-full py-2 px-3 text-xs">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[9px] font-black uppercase tracking-widest text-gray-400">From Name</label>
                                    <input type="text" name="mail_from_name" value="{{ $settings['smtpSettings']['mail_from_name']->value ?? '' }}" class="orbit-input w-full py-2 px-3 text-xs" placeholder="Admin Support">
                                </div>
                                <div class="space-y-1 md:col-span-3">
                                    <label class="text-[9px] font-black uppercase tracking-widest text-gray-400">From Address</label>
                                    <input type="email" name="mail_from_address" value="{{ $settings['smtpSettings']['mail_from_address']->value ?? '' }}" class="orbit-input w-full py-2 px-3 text-xs" placeholder="noreply@domain.com">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="orbit-btn">
                            Save Global Configuration
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Blockchain Section moved OUTSIDE the main form -->
        <div class="orbit-card overflow-hidden">
            <div class="p-8">
                <h3 class="text-xs font-black uppercase tracking-widest text-gray-900 mb-6 border-b border-gray-100 pb-4">Blockchain Clearing Nodes</h3>
                
                <div class="space-y-6">
                            @foreach($cryptoWallets as $wallet)
                            <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100 relative group">
                                <form action="{{ route('admin.settings.crypto.update', $wallet->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                                    @csrf
                                    <div class="space-y-1">
                                        <label class="text-[9px] font-black uppercase tracking-widest text-gray-400">Coin / Symbol</label>
                                        <div class="flex gap-2">
                                            <input type="text" name="coin_name" value="{{ $wallet->coin_name }}" class="orbit-input w-full py-2 px-3 text-xs" placeholder="Bitcoin">
                                            <input type="text" name="symbol" value="{{ $wallet->symbol }}" class="orbit-input w-20 py-2 px-3 text-xs" placeholder="BTC">
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[9px] font-black uppercase tracking-widest text-gray-400">Network</label>
                                        <input type="text" name="network" value="{{ $wallet->network }}" class="orbit-input w-full py-2 px-3 text-xs" placeholder="BTC Mainnet">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[9px] font-black uppercase tracking-widest text-gray-400">Wallet Address</label>
                                        <input type="text" name="wallet_address" value="{{ $wallet->wallet_address }}" class="orbit-input w-full py-2 px-3 text-xs font-mono" placeholder="0x...">
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="submit" class="bg-black text-white px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest hover:bg-gold hover:text-black transition-all">Update</button>
                                </form>
                                <form action="{{ route('admin.settings.crypto.destroy', $wallet->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-50 text-red-500 px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all">Decom</button>
                                </form>
                                    </div>
                            </div>
                            @endforeach

                <!-- Form for new wallet -->
                <div class="p-8 border-2 border-dashed border-gray-100 rounded-3xl bg-white mt-6">
                    <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-300 mb-6 text-center">Establish New Clearing Node</h4>
                    <form action="{{ route('admin.settings.crypto.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                        @csrf
                        <div class="space-y-1">
                            <label class="text-[9px] font-black uppercase tracking-widest text-gray-600">Coin Name</label>
                            <input type="text" name="coin_name" required class="orbit-input w-full" placeholder="e.g. Tether">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black uppercase tracking-widest text-gray-600">Symbol</label>
                            <input type="text" name="symbol" required class="orbit-input w-full" placeholder="e.g. USDT">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black uppercase tracking-widest text-gray-600">Protocol Network</label>
                            <input type="text" name="network" required class="orbit-input w-full" placeholder="e.g. TRC-20">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black uppercase tracking-widest text-gray-600">Target Address</label>
                            <input type="text" name="wallet_address" required class="orbit-input w-full font-mono" placeholder="Vault Address">
                        </div>
                        <div class="md:col-span-4 mt-4 flex justify-center">
                            <button type="submit" class="orbit-btn w-full md:w-auto px-12">
                                Authorize New Node
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
