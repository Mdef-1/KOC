<div class="p-6">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-2xl font-bold">Orders</h1>
        <div class="flex flex-wrap gap-2">
            <input type="text" wire:model.live="search" placeholder="Cari order..." 
                   class="border rounded-lg px-3 py-2 text-sm">
            <select wire:model.live="statusFilter" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Status</option>
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th wire:click="sortBy('order_number')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer">
                        Order Number
                        @if($sortField === 'order_number') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @endif
                    </th>
                    <th wire:click="sortBy('customer_name')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer">
                        Customer
                        @if($sortField === 'customer_name') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @endif
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                    <th wire:click="sortBy('total_quantity')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer">
                        Qty
                        @if($sortField === 'total_quantity') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @endif
                    </th>
                    <th wire:click="sortBy('status')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer">
                        Status
                        @if($sortField === 'status') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @endif
                    </th>
                    <th wire:click="sortBy('created_at')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer">
                        Date
                        @if($sortField === 'created_at') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @endif
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono text-sm">{{ $order->order_number }}</td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-sm">{{ $order->customer_name }}</div>
                            <div class="text-xs text-gray-500">{{ $order->customer_contact }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $order->product->name ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-3 text-sm">{{ $order->total_quantity }} pcs</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-full {{ $order->status_badge }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">
                            {{ $order->created_at->format('d M Y H:i') }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <button wire:click="openEditModal({{ $order->id }})" 
                                        class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Edit
                                </button>
                                <button wire:click="confirmDelete({{ $order->id }})" 
                                        class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                            Tidak ada order yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-4 py-3 border-t">
            {{ $orders->links() }}
        </div>
    </div>

    {{-- Edit Modal --}}
    @if($showModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-4">
                <div class="px-6 py-4 border-b flex items-center justify-between">
                    <h3 class="text-lg font-semibold">Edit Order {{ $editingOrder?->order_number }}</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">✕</button>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select wire:model="status" class="w-full border rounded-lg px-3 py-2">
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Satuan (opsional)</label>
                        <input type="number" wire:model="unitPrice" class="w-full border rounded-lg px-3 py-2" placeholder="Rp">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Harga (opsional)</label>
                        <input type="number" wire:model="totalPrice" class="w-full border rounded-lg px-3 py-2" placeholder="Rp">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Admin</label>
                        <textarea wire:model="adminNotes" rows="3" class="w-full border rounded-lg px-3 py-2"></textarea>
                    </div>
                </div>
                <div class="px-6 py-4 border-t flex justify-end gap-2">
                    <button wire:click="closeModal" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Batal</button>
                    <button wire:click="saveOrder" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Modal --}}
    @if($showDeleteModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-sm mx-4">
                <div class="px-6 py-4">
                    <h3 class="text-lg font-semibold mb-2">Konfirmasi Hapus</h3>
                    <p class="text-gray-600">Apakah Anda yakin ingin menghapus order ini?</p>
                </div>
                <div class="px-6 py-4 border-t flex justify-end gap-2">
                    <button wire:click="$set('showDeleteModal', false)" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Batal</button>
                    <button wire:click="deleteOrder" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Hapus</button>
                </div>
            </div>
        </div>
    @endif
</div>
