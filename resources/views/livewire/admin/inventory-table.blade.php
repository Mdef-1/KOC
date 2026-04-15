<div class="p-6 bg-white rounded-lg shadow">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Inventory Management</h2>
        <button wire:click="create"
            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none cursor-pointer touch-manipulation">
            Add New Item
        </button>
    </div>

    {{-- Alert --}}
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded relative">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded relative">
            {{ session('error') }}
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
        @if(count($groupedInventory) > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-8"></th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Total Sizes</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Total Stock</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($groupedInventory as $productId => $items)
                        @php
                            $firstItem = $items->first();
                            $product = $firstItem->product;
                            $isExpanded = in_array($productId, $expandedProducts);
                            $totalStock = $items->sum('stock');
                            $totalSizes = $items->count();
                        @endphp
                        {{-- Main Product Row --}}
                        <tr class="hover:bg-gray-50 {{ $isExpanded ? 'bg-blue-50' : '' }}">
                            <td class="px-4 py-4">
                                <button type="button"
                                    wire:click="toggleProduct({{ $productId }})"
                                    class="w-6 h-6 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 transition-colors cursor-pointer touch-manipulation">
                                    <svg class="w-4 h-4 text-gray-600 transform {{ $isExpanded ? 'rotate-90' : '' }} transition-transform" 
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $product->name ?? 'Unknown' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $product->category->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-center text-sm text-gray-600">
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                    {{ $totalSizes }} size{{ $totalSizes > 1 ? 's' : '' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-sm font-semibold {{ $totalStock > 10 ? 'text-green-600' : ($totalStock > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ $totalStock }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <button type="button"
                                    wire:click="createWithProduct({{ $productId }})"
                                    class="text-blue-600 hover:text-blue-900 px-2 py-1 rounded hover:bg-blue-50 text-xs cursor-pointer touch-manipulation">
                                    + Add Size
                                </button>
                            </td>
                        </tr>
                        
                        {{-- Expanded Size Details --}}
                        @if($isExpanded)
                            <tr>
                                <td colspan="6" class="px-0 py-0 bg-gray-50">
                                    <div class="px-4 py-3">
                                        <table class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-lg overflow-hidden">
                                            <thead class="bg-gray-100">
                                                <tr>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Size</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Warehouse</th>
                                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Stock</th>
                                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Cost</th>
                                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Price</th>
                                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-100">
                                                @foreach($items as $item)
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $item->size->label ?? 'N/A' }}</td>
                                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $item->sku }}</td>
                                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $item->warehouse_location ?? '-' }}</td>
                                                        <td class="px-4 py-3 text-right">
                                                            <div class="flex items-center justify-end gap-1">
                                                                <button type="button"
                                                                    wire:click="quickAdjustStock({{ $item->id }}, -1)"
                                                                    class="w-5 h-5 flex items-center justify-center rounded bg-red-100 text-red-600 hover:bg-red-200 transition-colors cursor-pointer touch-manipulation"
                                                                    title="Kurangi 1">
                                                                    <svg class="w-3 h-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                                                    </svg>
                                                                </button>
                                                                <span class="px-2 text-xs font-semibold {{ $item->stock > 10 ? 'text-green-600' : ($item->stock > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                                                                    {{ $item->stock }}
                                                                </span>
                                                                <button type="button"
                                                                    wire:click="quickAdjustStock({{ $item->id }}, 1)"
                                                                    class="w-5 h-5 flex items-center justify-center rounded bg-green-100 text-green-600 hover:bg-green-200 transition-colors cursor-pointer touch-manipulation"
                                                                    title="Tambah 1">
                                                                    <svg class="w-3 h-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </td>
                                                        <td class="px-4 py-3 text-right text-sm">Rp {{ number_format($item->cost_price, 0, ',', '.') }}</td>
                                                        <td class="px-4 py-3 text-right text-sm">Rp {{ number_format($item->final_price, 0, ',', '.') }}</td>
                                                        <td class="px-4 py-3 text-center text-sm space-x-1">
                                                            <button type="button"
                                                                wire:click="openStockModal({{ $item->id }})"
                                                                class="text-blue-600 hover:text-blue-800 px-1 cursor-pointer touch-manipulation"
                                                                title="Adjust Stock">
                                                                <svg class="w-4 h-4 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                                                </svg>
                                                            </button>
                                                            <button type="button"
                                                                wire:click="edit({{ $item->id }})"
                                                                class="text-indigo-600 hover:text-indigo-900 px-1 cursor-pointer touch-manipulation">
                                                                Edit
                                                            </button>
                                                            <button type="button"
                                                                wire:key="delete-{{ $item->id }}"
                                                                wire:click="delete({{ $item->id }})"
                                                                onclick="return confirm('Yakin hapus item ini?')"
                                                                class="text-red-600 hover:text-red-900 px-1 cursor-pointer touch-manipulation"
                                                                title="Hapus item">
                                                                Del
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">{{ $groupedInventory->links() }}</div>
        @else
            <div class="text-center py-12 bg-gray-50 rounded-lg">No inventory items found.</div>
        @endif
    </div>

    {{-- Modal Create/Edit --}}
    @if($isOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto" wire:key="inventory-modal-{{ $product_id ?? 'new' }}" x-data="{ open: true }" x-show="open" x-on:keydown.escape.window="open = false; $wire.$set('isOpen', false)">
            <div class="flex items-center justify-center min-h-screen px-4 py-6 text-center">
                <div class="fixed inset-0 bg-black/50 transition-opacity" wire:click="$set('isOpen', false)"></div>

                <div
                    class="relative w-full max-w-4xl mx-auto overflow-hidden text-left bg-white rounded-lg shadow-xl max-h-[90vh] overflow-y-auto">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900">{{ $selected_id ? 'Edit' : 'Add New' }} Inventory
                                Item</h3>
                            <button type="button" wire:click="$set('isOpen', false)"
                                class="text-gray-400 hover:text-gray-500 cursor-pointer touch-manipulation">✕</button>
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

                                {{-- PRODUCT SELECTION --}}
                                <div class="sm:col-span-2" wire:key="product-select-{{ $isOpen }}">
                                    <label class="block text-sm font-medium text-gray-700">Product <span
                                            class="text-red-500">*</span></label>
                                    <select wire:model.live="product_id"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm max-w-full cursor-pointer touch-manipulation">
                                        <option value="">Select a product</option>
                                        @foreach(\App\Models\Product::with('category')->orderBy('name', 'asc')->get() as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                    @if($product_id)
                                        @php
                                            $selectedProduct = \App\Models\Product::with('category')->find($product_id);
                                        @endphp
                                        @if($selectedProduct && $selectedProduct->category)
                                            <p class="mt-1 text-xs text-blue-600">Kategori: {{ $selectedProduct->category->name }}</p>
                                        @endif
                                    @endif
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

                                {{-- NEW: SIZE SELECTION --}}
                                <div class="sm:col-span-2" wire:key="size-select-{{ $product_id }}">
                                    <label class="block text-sm font-medium text-gray-700">Size <span
                                            class="text-red-500">*</span></label>
                                    <select wire:model="size_id"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm max-w-full cursor-pointer touch-manipulation">
                                        <option value="">Select a size</option>
                                        @forelse($sizes as $size)
                                            <option value="{{ $size->id }}">{{ $size->label }} ({{ $size->width }}x{{ $size->length }})</option>
                                        @empty
                                            <option value="" disabled>Belum ada size untuk kategori ini</option>
                                        @endforelse
                                    </select>
                                    @if($product_id)
                                        @php
                                            $selectedProduct = \App\Models\Product::with('category')->find($product_id);
                                        @endphp
                                        @if($selectedProduct && $selectedProduct->category)
                                            <p class="mt-1 text-xs text-gray-500">Menampilkan size untuk kategori: <span class="font-medium text-blue-600">{{ $selectedProduct->category->name }}</span></p>
                                        @endif
                                    @endif
                                    @error('size_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
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
    {{-- Stock Adjustment Modal --}}
    @if($isStockModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ open: true }" x-show="open" x-on:keydown.escape.window="open = false; $wire.closeStockModal()">
            <div class="flex items-center justify-center min-h-screen px-4 py-6 text-center">
                <div class="fixed inset-0 bg-black/50 transition-opacity" wire:click="closeStockModal"></div>
                <div class="relative w-full max-w-md mx-auto overflow-hidden text-left bg-white rounded-lg shadow-xl max-h-[90vh] overflow-y-auto">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900">Penyesuaian Stok</h3>
                            <button type="button" wire:click="closeStockModal" class="text-gray-400 hover:text-gray-500">✕</button>
                        </div>
                    </div>

                    <form wire:submit.prevent="adjustStock">
                        <div class="px-6 py-6 space-y-4">
                            {{-- Global Error Alert --}}
                            @if ($errors->has('adjustmentQty'))
                                <div class="p-3 bg-red-50 border border-red-200 rounded-md">
                                    <p class="text-sm text-red-600">{{ $errors->first('adjustmentQty') }}</p>
                                </div>
                            @endif

                            {{-- Adjustment Type --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Penyesuaian</label>
                                <div class="flex gap-3">
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" wire:model="adjustmentType" value="in" class="sr-only peer">
                                        <div class="px-4 py-3 text-center rounded-lg border-2 border-gray-200 peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:text-green-700 transition-all">
                                            <span class="block text-sm font-medium">Stok Masuk (+)</span>
                                        </div>
                                    </label>
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" wire:model="adjustmentType" value="out" class="sr-only peer">
                                        <div class="px-4 py-3 text-center rounded-lg border-2 border-gray-200 peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:text-red-700 transition-all">
                                            <span class="block text-sm font-medium">Stok Keluar (-)</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            {{-- Quantity --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                                <input type="number" wire:model="adjustmentQty" min="1"
                                    class="mt-1 block w-full border border-gray-300 rounded-md py-2 px-3 text-sm focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Masukkan jumlah">
                                @error('adjustmentQty') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            {{-- Note --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                                <input type="text" wire:model="adjustmentNote"
                                    class="mt-1 block w-full border border-gray-300 rounded-md py-2 px-3 text-sm focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Contoh: Penyesuaian stock opname">
                            </div>

                            {{-- Preview --}}
                            @if($adjustmentQty > 0 && $adjustmentInventoryId)
                                @php
                                    $inv = \App\Models\Inventory::find($adjustmentInventoryId);
                                    $newStock = $adjustmentType === 'in' 
                                        ? $inv->stock + $adjustmentQty 
                                        : $inv->stock - $adjustmentQty;
                                @endphp
                                <div class="p-3 bg-gray-50 rounded-lg text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Stok saat ini:</span>
                                        <span class="font-medium">{{ $inv->stock }}</span>
                                    </div>
                                    <div class="flex justify-between mt-1">
                                        <span class="text-gray-500">Setelah penyesuaian:</span>
                                        <span class="font-medium {{ $newStock < 0 ? 'text-red-600' : ($adjustmentType === 'in' ? 'text-green-600' : 'text-orange-600') }}">
                                            {{ $newStock }}
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end space-x-3">
                            <button type="button" wire:click="closeStockModal"
                                class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                Batal
                            </button>
                            <button type="submit" wire:loading.attr="disabled"
                                class="px-4 py-2 text-sm text-white {{ $adjustmentType === 'in' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }} rounded-md transition-colors flex items-center">
                                <span wire:loading.remove>Simpan Penyesuaian</span>
                                <span wire:loading class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Memproses...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>