<x-admin-layout>
    <div x-data="{ 
        addModal: false, 
        editModal: false, 
        deleteModal: false,
        selectedProduct: {
            id: null,
            name: '',
            metal: 'gold',
            category: 'coin',
            weight: 1,
            weight_unit: 'oz',
            purity: 0.9999,
            base_price: 0,
            premium_percentage: 0,
            premium_fixed: 0,
            stock: 0,
            image_url: null
        },
        openEdit(product) {
            this.selectedProduct = { ...product };
            this.editModal = true;
        },
        openDelete(product) {
            this.selectedProduct = { ...product };
            this.deleteModal = true;
        }
    }" class="space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black tracking-tighter uppercase text-gray-900 leading-none">Asset <span class="text-gray-400">Vault</span></h1>
                <div class="flex items-center gap-2 mt-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-gold"></span>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Global Institutional Asset Registry</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <button @click="addModal = true" class="orbit-btn-primary shadow-lg shadow-black/5">+ Register Asset</button>
            </div>
        </div>

        <!-- Utility Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 orbit-card p-6">
            <div class="flex items-center space-x-8">
                <button class="text-[10px] font-black uppercase tracking-widest text-gold border-b-2 border-gold pb-1">All Assets</button>
                <button class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-gray-600 transition-colors pb-1">Gold</button>
                <button class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-gray-600 transition-colors pb-1">Silver</button>
            </div>
            <div class="flex items-center gap-4">
                <button class="bg-white border border-gray-100 px-4 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-600 hover:bg-gray-50 shadow-sm transition-all">Export Catalog</button>
                <button class="bg-gray-50 px-4 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-600 hover:bg-gray-100 transition-all">Audit Mode</button>
            </div>
        </div>

        <!-- Asset Table -->
        <div class="orbit-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr>
                            <th class="orbit-table-th">Asset Visual</th>
                            <th class="orbit-table-th">Name & Registry</th>
                            <th class="orbit-table-th text-gold">Settlement Price</th>
                            <th class="orbit-table-th">Premiums</th>
                            <th class="orbit-table-th">Inventory</th>
                            <th class="orbit-table-th text-right">Verification Protocols</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($products as $product)
                        <tr class="hover:bg-gray-50/50 transition-all group">
                            <td class="orbit-table-td">
                                <div class="h-12 w-12 rounded-xl bg-gray-50 border border-gray-100 overflow-hidden flex items-center justify-center shadow-inner shadow-black/5">
                                    @if($product->image_path)
                                        <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                                    @else
                                        <span class="text-[10px] font-black text-gray-300 uppercase">Empty</span>
                                    @endif
                                </div>
                            </td>
                            <td class="orbit-table-td">
                                <div class="flex flex-col">
                                    <span class="text-[11px] font-bold text-gray-900 group-hover:text-gold transition-colors">{{ $product->name }}</span>
                                    <span class="text-[9px] text-gray-400 font-bold tracking-widest uppercase mt-0.5">{{ $product->metal }} {{ $product->category }}</span>
                                </div>
                            </td>
                            <td class="orbit-table-td">
                                <span class="text-[13px] font-black {{ strpos($product->name, 'Institutional Digital') !== false ? 'text-gold' : 'text-gray-900' }} group-hover:text-gold transition-colors">
                                    {{ \App\Services\CurrencyService::formatNative($product->current_price) }}
                                </span>
                                <div class="text-[9px] text-gray-400 font-bold uppercase mt-0.5">
                                    {{ $product->weight }}{{ strtoupper($product->weight_unit) }} • {{ $product->purity * 100 }}% PRTY
                                </div>
                            </td>
                            <td class="orbit-table-td">
                                <div class="flex flex-col">
                                    <span class="text-[11px] font-black text-gray-900">+{{ $product->premium_percentage }}%</span>
                                    <span class="text-[9px] text-gray-400 font-bold uppercase mt-0.5">+{{ \App\Services\CurrencyService::formatNative($product->premium_fixed) }} FIX</span>
                                </div>
                            </td>
                            <td class="orbit-table-td">
                                <span class="orbit-status-badge {{ $product->stock > 0 ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                                    {{ $product->stock }} REG
                                </span>
                            </td>
                            <td class="orbit-table-td text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <button @click="openEdit({{ json_encode($product) }})" class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-gold transition-colors">Edit</button>
                                    <button @click="openDelete({{ json_encode($product) }})" class="text-[10px] font-black uppercase tracking-widest text-red-400 hover:text-red-600 transition-colors">Delete</button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Asset Modal -->
        <div x-show="addModal" x-cloak class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
                <div x-show="addModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="addModal = false"></div>
                <div x-show="addModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-[2rem] bg-white p-8 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-gray-100">
                    <div class="mb-8">
                        <h3 class="text-xl font-black tracking-tighter uppercase text-gray-900">Register New <span class="text-gray-400">Institutional Asset</span></h3>
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-1">Populate global vault metadata</p>
                    </div>
                    
                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-2 col-span-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Asset Identity</label>
                                <input type="text" name="name" required class="orbit-input w-full font-bold" placeholder="e.g. 1oz American Gold Eagle">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Composition</label>
                                <select name="metal" class="orbit-input w-full font-bold">
                                    <option value="gold">Gold (AU)</option>
                                    <option value="silver">Silver (AG)</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Module Category</label>
                                <select name="category" class="orbit-input w-full font-bold">
                                    <option value="coin">Coinage</option>
                                    <option value="bar">Bullion Bar</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Unit Weight</label>
                                <input type="number" name="weight" step="0.01" required class="orbit-input w-full font-bold" placeholder="1.00">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Weight Protocol</label>
                                <input type="text" name="weight_unit" value="oz" required class="orbit-input w-full font-bold">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Purity Level</label>
                                <input type="number" name="purity" step="0.0001" value="0.9999" required class="orbit-input w-full font-bold">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Inventory stock</label>
                                <input type="number" name="stock" value="0" required class="orbit-input w-full font-bold">
                            </div>
                             <div class="space-y-2 col-span-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gold ml-1">Market Price (Static Settlement)</label>
                                <input type="number" name="base_price" step="0.01" value="0" required class="orbit-input w-full font-bold border-gold/30 hover:border-gold focus:border-gold" placeholder="0.00">
                                <p class="text-[8px] text-gray-400 font-bold uppercase mt-1 tracking-wider italic">* This value will be the primary settlement price for guest and vault acquisitions.</p>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Premium (%)</label>
                                <input type="number" name="premium_percentage" step="0.01" value="0" required class="orbit-input w-full font-bold">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Settlement Fix ($)</label>
                                <input type="number" name="premium_fixed" step="0.01" value="0" required class="orbit-input w-full font-bold">
                            </div>
                            <div class="space-y-2 col-span-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Visual Identity (Image)</label>
                                <input type="file" name="image" class="orbit-input w-full font-bold text-[10px] file:orbit-btn-primary file:mr-4 file:!p-1.5 file:!text-[9px] file:border-none cursor-pointer">
                            </div>
                        </div>
                        <div class="pt-6 flex gap-3">
                            <button type="submit" class="orbit-btn-primary flex-1 py-4">Register Asset</button>
                            <button type="button" @click="addModal = false" class="bg-gray-50 px-6 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-500 hover:bg-gray-100 transition-all">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Asset Modal -->
        <div x-show="editModal" x-cloak class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
                <div x-show="editModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="editModal = false"></div>
                <div x-show="editModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-[2rem] bg-white p-8 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-gray-100">
                    <div class="mb-8">
                        <h3 class="text-xl font-black tracking-tighter uppercase text-gray-900">Orchestrate <span class="text-gray-400">Asset Metadata</span></h3>
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-1">Security Ref: #<span x-text="selectedProduct.id"></span></p>
                    </div>
                    
                    <form :action="'{{ url('admin/products') }}/' + selectedProduct.id" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-2 col-span-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Asset Identity</label>
                                <input type="text" name="name" x-model="selectedProduct.name" required class="orbit-input w-full font-bold">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Composition</label>
                                <select name="metal" x-model="selectedProduct.metal" class="orbit-input w-full font-bold">
                                    <option value="gold">Gold (AU)</option>
                                    <option value="silver">Silver (AG)</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Module Category</label>
                                <select name="category" x-model="selectedProduct.category" class="orbit-input w-full font-bold">
                                    <option value="coin">Coinage</option>
                                    <option value="bar">Bullion Bar</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Unit Weight</label>
                                <input type="number" name="weight" x-model="selectedProduct.weight" step="0.01" required class="orbit-input w-full font-bold">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Weight Protocol</label>
                                <input type="text" name="weight_unit" x-model="selectedProduct.weight_unit" required class="orbit-input w-full font-bold">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Purity Level</label>
                                <input type="number" name="purity" x-model="selectedProduct.purity" step="0.0001" required class="orbit-input w-full font-bold">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Inventory stock</label>
                                <input type="number" name="stock" x-model="selectedProduct.stock" required class="orbit-input w-full font-bold">
                            </div>
                             <div class="space-y-2 col-span-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gold ml-1">Market Price (Static Settlement)</label>
                                <input type="number" name="base_price" x-model="selectedProduct.base_price" step="0.01" required class="orbit-input w-full font-bold border-gold/30 hover:border-gold focus:border-gold">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Premium (%)</label>
                                <input type="number" name="premium_percentage" x-model="selectedProduct.premium_percentage" step="0.01" required class="orbit-input w-full font-bold">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Settlement Fix ($)</label>
                                <input type="number" name="premium_fixed" x-model="selectedProduct.premium_fixed" step="0.01" required class="orbit-input w-full font-bold">
                            </div>
                            <div class="space-y-2 col-span-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Visual Identity Transfer (Image)</label>
                                <input type="file" name="image" class="orbit-input w-full font-bold text-[10px] file:orbit-btn-primary file:mr-4 file:!p-1.5 file:!text-[9px] file:border-none cursor-pointer">
                                <p class="text-[9px] text-gray-400 font-bold uppercase mt-2">Leave blank to retain current visual protocol</p>
                            </div>
                        </div>
                        <div class="pt-6 flex gap-3">
                            <button type="submit" class="orbit-btn-primary flex-1 py-4">Save Orchestration</button>
                            <button type="button" @click="editModal = false" class="bg-gray-50 px-6 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-500 hover:bg-gray-100 transition-all">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Decommission Asset Modal -->
        <div x-show="deleteModal" x-cloak class="fixed inset-0 z-[110] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
                <div x-show="deleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" @click="deleteModal = false"></div>
                
                <div x-show="deleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-[2rem] bg-white p-10 text-center shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-gray-100">
                    <div class="mb-6 flex justify-center">
                        <div class="w-16 h-16 rounded-2xl bg-red-50 flex items-center justify-center text-red-500 shadow-inner">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        </div>
                    </div>
                    <div class="mb-8">
                        <h3 class="text-xl font-black tracking-tighter uppercase text-gray-900">Purge <span class="text-red-500">Asset Protocol?</span></h3>
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-2 leading-relaxed">
                            You are about to permanently decommission <span class="text-gray-900" x-text="selectedProduct.name"></span> from the institutional registry. This action is irreversible.
                        </p>
                    </div>
                    
                    <form :action="'{{ url('admin/products') }}/' + selectedProduct.id" method="POST" class="space-y-3">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-600 text-white font-black text-[10px] uppercase tracking-[0.2em] py-4 rounded-xl shadow-lg shadow-red-200 hover:bg-red-700 transition-all">Authorize Decommissioning</button>
                        <button type="button" @click="deleteModal = false" class="w-full bg-gray-50 text-gray-500 font-black text-[10px] uppercase tracking-[0.2em] py-4 rounded-xl hover:bg-gray-100 transition-all">Abort Protocol</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
