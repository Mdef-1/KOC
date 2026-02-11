<div class="p-6 bg-white rounded-lg shadow">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Inventory Management</h2>
        <button wire:click="create"
            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none">
            Add New Item
        </button>
    </div>

    {{-- Alert --}}
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded relative">
            {{ session('message') }}
        </div>
    @endif

    {{-- Search & Filter --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
        <div class="relative w-full sm:w-96 group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by product name or SKU..."
                class="block w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 sm:text-sm">
        </div>
    </div>

    {{-- Table Section --}}
    <div class="overflow-x-auto">
        @if(count($inventory) > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Qty</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Price</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($inventory as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item->product->name ?? 'Unknown' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $item->sku }}</td>
                            <td class="px-6 py-4 text-right">
                                <span
                                    class="px-2 inline-flex text-xs font-semibold rounded-full {{ $item->stock > 10 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $item->stock }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">${{ number_format($item->price, 2) }}</td>
                            <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                                <button wire:click="edit({{ $item->id }})"
                                    class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                <button wire:click="delete({{ $item->id }})"
                                    class="text-red-600 hover:text-red-900">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">{{ $inventory->links() }}</div>
        @else
            <div class="text-center py-12 bg-gray-50 rounded-lg">No inventory items found.</div>
        @endif
    </div>

    {{-- Modal Create/Edit --}}
    @if($isOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-black/45 transition-opacity" wire:click="$set('isOpen', false)"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div
                    class="relative inline-block w-full max-w-4xl overflow-hidden text-left align-middle transition-all transform bg-white rounded-lg shadow-xl">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900">{{ $selected_id ? 'Edit' : 'Add New' }} Inventory
                                Item</h3>
                            <button type="button" wire:click="$set('isOpen', false)"
                                class="text-gray-400 hover:text-gray-500">✕</button>
                        </div>
                    </div>

                    <form wire:submit.prevent="store">
                        <div class="px-6 py-4 space-y-6">
                            {{-- Alert Error Global (Opsional tapi sangat membantu buat debugging) --}}
                            @if ($errors->any())
                                <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm">
                                    Harap periksa kembali inputan Anda. Ada beberapa data yang belum lengkap.
                                </div>
                            @endif

                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                                {{-- SELECT2 PRODUCT SELECTION --}}
                                <div class="sm:col-span-2" wire:ignore x-data="{ 
                        initSelect2() {
                            let el = $(this.$refs.prodSelect);
                            el.select2({
                                placeholder: 'Select a product',
                                allowClear: true,
                                width: '100%',
                                dropdownParent: el.parent()
                            }).on('change', (e) => {
                                @this.set('product_id', e.target.value);
                            });
                        }
                    }" x-init="initSelect2(); $watch('$wire.product_id', value => $( $refs.prodSelect ).val(value).trigger('change'))">

                                    <label class="block text-sm font-medium text-gray-700">Product <span
                                            class="text-red-500">*</span></label>
                                    <select x-ref="prodSelect"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                                        <option value="">Select a product</option>
                                        @foreach(\App\Models\Product::all() as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('product_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                {{-- NEW: WAREHOUSE LOCATION --}}
                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Warehouse Location <span
                                            class="text-red-500">*</span></label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                        </div>
                                        <input type="text" wire:model="warehouse_location"
                                            class="block w-full pl-10 border border-gray-300 rounded-md py-2 px-3 text-sm focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Contoh: Rak A-1 atau Gudang Utama">
                                    </div>
                                    @error('warehouse_location') <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- SKU --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">SKU <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" wire:model="sku"
                                        class="mt-1 block w-full border border-gray-300 rounded-md py-2 px-3 text-sm"
                                        placeholder="SKU">
                                    @error('sku') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                {{-- Stock --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Stock <span
                                            class="text-red-500">*</span></label>
                                    <input type="number" wire:model="stock"
                                        class="mt-1 block w-full border border-gray-300 rounded-md py-2 px-3 text-sm">
                                    @error('stock') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                {{-- Price --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Selling Price <span
                                            class="text-red-500">*</span></label>
                                    <input type="number" step="0.01" wire:model="price"
                                        class="mt-1 block w-full border border-gray-300 rounded-md py-2 px-3 text-sm"
                                        placeholder="0.00">
                                    @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                {{-- Cost Price --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Cost Price <span
                                            class="text-red-500">*</span></label>
                                    <input type="number" step="0.01" wire:model="cost_price"
                                        class="mt-1 block w-full border border-gray-300 rounded-md py-2 px-3 text-sm"
                                        placeholder="0.00">
                                    @error('cost_price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end space-x-3 rounded-b-lg">
                            <button type="button" wire:click="$set('isOpen', false)"
                                class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" wire:loading.attr="disabled"
                                class="px-4 py-2 text-sm text-white bg-blue-600 rounded-md hover:bg-blue-700 transition-colors flex items-center">
                                <span wire:loading.remove>{{ $selected_id ? 'Update' : 'Create' }} Item</span>
                                <span wire:loading class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    Processing...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>