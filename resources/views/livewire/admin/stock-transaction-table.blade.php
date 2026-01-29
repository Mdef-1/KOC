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

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
        <div class="relative w-full sm:w-96 group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by product name"
                class="block w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 sm:text-sm">
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Qty</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($transactions as $trx)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $trx->inventory->product->name ?? 'N/A' }}
                            </div>
                            <div class="text-xs text-gray-500">SKU: {{ $trx->inventory->sku ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="px-2 py-1 text-xs font-semibold rounded-full {{ $trx->type == 'in' ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800' }}">
                                {{ strtoupper($trx->type) }}
                            </span>
                        </td>
                        <td
                            class="px-6 py-4 text-right font-bold {{ $trx->type == 'in' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $trx->type == 'in' ? '+' : '-' }}{{ number_format($trx->quantity) }}
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium space-x-3">
                            <button wire:click="edit({{ $trx->id }})"
                                class="text-indigo-600 hover:text-indigo-900">Edit</button>
                            <button wire:click="delete({{ $trx->id }})"
                                onclick="confirm('Hapus transaksi?') || event.stopImmediatePropagation()"
                                class="text-red-600 hover:text-red-900">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">No transactions found.</td>
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

                            <div class="sm:col-span-2" wire:ignore x-data="{ 
                                        initSelect2() {
                                            let el = $(this.$refs.select);
                                            el.select2({
                                                placeholder: '-- Select SKU / Product --',
                                                allowClear: true,
                                                width: '100%',
                                                dropdownParent: el.parent(),
                                                matcher: function(params, data) {
                                                    if ($.trim(params.term) === '') return data;
                                                    let term = params.term.toUpperCase();
                                                    let text = data.text.toUpperCase();
                                                    // Match dari depan (Start With) atau setelah spasi
                                                    return (text.indexOf(term) === 0 || text.indexOf(' ' + term) > -1) ? data : null;
                                                }
                                            }).on('change', (e) => {
                                                @this.set('inventory_id', e.target.value);
                                            });
                                        }
                                    }" x-init="
                                        initSelect2();
                                        $watch('$wire.inventory_id', value => $( $refs.select ).val(value).trigger('change'));
                                    ">

                                <label class="block text-sm font-medium text-gray-700">Inventory Item <span
                                        class="text-red-500">*</span></label>
                                <select x-ref="select" id="select2-inventory"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">-- Select SKU / Product --</option>
                                    @foreach($inventory_items as $item)
                                        <option value="{{ $item->id }}" {{ $inventory_id == $item->id ? 'selected' : '' }}>
                                            {{ $item->sku }} - {{ $item->product->name ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('inventory_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Type</label>
                                <select wire:model.defer="type"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                                    <option value="in">Stock In (+)</option>
                                    <option value="out">Stock Out (-)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                <input type="number" wire:model.defer="quantity"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                                @error('quantity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea wire:model.defer="description" rows="3"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm"></textarea>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" wire:click="$set('isOpen', false)"
                                class="bg-white border px-4 py-2 rounded-md text-sm">Cancel</button>
                            <button type="submit" class="bg-blue-600 px-4 py-2 rounded-md text-sm text-white">Save
                                Transaction</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>