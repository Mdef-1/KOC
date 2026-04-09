<div class="p-6 bg-white rounded-lg shadow">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Stock Transactions</h2>
        <button wire:click="create"
            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition-colors">
            Record Transaction
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- Filter Section -->
    <div class="bg-gray-50 rounded-lg p-4 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search Product -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Search Product</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Product name..."
                        class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
            </div>

            <!-- Product Filter -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Product</label>
                <select wire:model.live="productFilter" class="block w-full py-2 px-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    <option value="">All Products</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Size Filter -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Size</label>
                <select wire:model.live="sizeFilter" class="block w-full py-2 px-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    <option value="">All Sizes</option>
                    @foreach($sizes as $size)
                        <option value="{{ $size->id }}">{{ $size->label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Type Filter -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Type</label>
                <select wire:model.live="typeFilter" class="block w-full py-2 px-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    <option value="">All Types</option>
                    <option value="in">Stock In</option>
                    <option value="out">Stock Out</option>
                </select>
            </div>

            <!-- Date From -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Date From</label>
                <input type="date" wire:model.live="dateFrom" class="block w-full py-2 px-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
            </div>

            <!-- Date To -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Date To</label>
                <input type="date" wire:model.live="dateTo" class="block w-full py-2 px-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
            </div>

            <!-- Reference Filter -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Reference</label>
                <input type="text" wire:model.live.debounce.300ms="referenceFilter" placeholder="Invoice/PO number..."
                    class="block w-full py-2 px-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
            </div>

            <!-- Reset Button -->
            <div class="flex items-end">
                <button wire:click="resetFilters" class="w-full py-2 px-4 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                    Reset Filters
                </button>
            </div>

            <!-- Show Trashed Toggle -->
            <div class="flex items-end">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" wire:model.live="showTrashed" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="text-sm text-gray-700">Show Deleted</span>
                </label>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th wire:click="sortBy('transaction_id')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100 select-none">
                        <div class="flex items-center gap-1">
                            Transaction ID
                            @if($sortField === 'transaction_id')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                </svg>
                            @endif
                        </div>
                    </th>
                    <th wire:click="sortBy('product_id')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100 select-none">
                        <div class="flex items-center gap-1">
                            Product
                            @if($sortField === 'product_id')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                </svg>
                            @endif
                        </div>
                    </th>
                    <th wire:click="sortBy('size_id')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100 select-none">
                        <div class="flex items-center gap-1">
                            Size
                            @if($sortField === 'size_id')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                </svg>
                            @endif
                        </div>
                    </th>
                    <th wire:click="sortBy('type')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100 select-none">
                        <div class="flex items-center gap-1">
                            Type
                            @if($sortField === 'type')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                </svg>
                            @endif
                        </div>
                    </th>
                    <th wire:click="sortBy('quantity')" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100 select-none">
                        <div class="flex items-center justify-end gap-1">
                            Qty
                            @if($sortField === 'quantity')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                </svg>
                            @endif
                        </div>
                    </th>
                    <th wire:click="sortBy('reference_id')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100 select-none">
                        <div class="flex items-center gap-1">
                            Reference
                            @if($sortField === 'reference_id')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                </svg>
                            @endif
                        </div>
                    </th>
                    <th wire:click="sortBy('new_stock')" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase cursor-pointer hover:bg-gray-100 select-none">
                        <div class="flex items-center justify-center gap-1">
                            Stock
                            @if($sortField === 'new_stock')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                </svg>
                            @endif
                        </div>
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($transactions as $trx)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-mono text-gray-900">{{ $trx->transaction_id }}</div>
                            <div class="text-xs text-gray-500">{{ $trx->created_at->format('d M Y H:i') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $trx->product->name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $trx->size->label ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $trx->type == 'in' ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800' }}">
                                {{ strtoupper($trx->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-bold {{ $trx->type == 'in' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $trx->type == 'in' ? '+' : '-' }}{{ number_format($trx->quantity) }}
                        </td>
                        <td class="px-6 py-4">2101
                        
                            <div class="text-sm text-gray-900">{{ $trx->reference_id ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="text-xs text-gray-500">{{ $trx->old_stock }} → {{ $trx->new_stock }}</div>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium space-x-3">
                            @if($showTrashed)
                                <button wire:click="confirmRestore({{ $trx->id }})" class="text-green-600 hover:text-green-900">Restore</button>
                            @else
                                <button wire:click="edit({{ $trx->id }})" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                <button wire:click="confirmDelete({{ $trx->id }})" class="text-red-600 hover:text-red-900">Delete</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">No transactions found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $transactions->links() }}</div>
    </div>

    @if($isOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black/50 transition-opacity" wire:click="$set('isOpen', false)"></div>

                <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $selected_id ? 'Edit' : 'Record' }} Stock Movement
                    </h3>

                    <form wire:submit.prevent="store">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Product <span class="text-red-500">*</span></label>
                                <select wire:model="product_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">-- Select Product --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                                @error('product_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Size <span class="text-red-500">*</span></label>
                                <select wire:model="size_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">-- Select Size --</option>
                                    @foreach($sizes as $size)
                                        <option value="{{ $size->id }}">{{ $size->label }}</option>
                                    @endforeach
                                </select>
                                @error('size_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Type</label>
                                <select wire:model="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                                    <option value="in">Stock In (+)</option>
                                    <option value="out">Stock Out (-)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                <input type="number" wire:model="quantity" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                                @error('quantity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Reference ID</label>
                                <input type="text" wire:model="reference_id" placeholder="Nomor Invoice / PO" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                                @error('reference_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea wire:model="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm"></textarea>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" wire:click="$set('isOpen', false)"
                                class="bg-white border px-4 py-2 rounded-md text-sm">Cancel</button>
                            <button type="submit" class="bg-blue-600 px-4 py-2 rounded-md text-sm text-white">Save Transaction</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Konfirmasi -->
    @if($isConfirmOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black/50 transition-opacity" wire:click="closeConfirm"></div>

                <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 rounded-full {{ $confirmAction === 'delete' ? 'bg-red-100' : 'bg-green-100' }}">
                        @if($confirmAction === 'delete')
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        @else
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        @endif
                    </div>

                    <h3 class="text-lg font-medium text-gray-900 text-center mb-2">{{ $confirmTitle }}</h3>
                    <p class="text-sm text-gray-500 text-center mb-6">{{ $confirmMessage }}</p>

                    <div class="flex justify-center space-x-3">
                        <button type="button" wire:click="closeConfirm"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors">
                            Batal
                        </button>
                        <button type="button" wire:click="executeConfirm" wire:loading.attr="disabled"
                            class="px-4 py-2 text-sm font-medium text-white rounded-md transition-colors {{ $confirmAction === 'delete' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }}">
                            <span wire:loading.remove wire:target="executeConfirm">Ya, Lanjutkan</span>
                            <span wire:loading wire:target="executeConfirm">Memproses...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Loading -->
    <div wire:loading.flex wire:target="executeConfirm, updatingSearch, updatingProductFilter, updatingSizeFilter, updatingTypeFilter, updatingDateFrom, updatingDateTo, updatingReferenceFilter, updatingShowTrashed, sortBy, resetFilters" class="fixed inset-0 z-50 items-center justify-center bg-black/30">
        <div class="bg-white rounded-lg shadow-xl p-6 flex flex-col items-center">
            <svg class="animate-spin h-8 w-8 text-blue-600 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-sm font-medium text-gray-700">Loading...</span>
        </div>
    </div>
</div>