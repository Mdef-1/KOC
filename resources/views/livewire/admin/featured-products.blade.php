<div class="p-6 max-w-7xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Kelola Produk Unggulan</h1>
        <p class="mt-2 text-gray-600">Pilih produk yang akan ditampilkan di halaman utama (Best Collection).</p>
        <div class="mt-4 p-4 bg-blue-50 rounded-lg">
            <p class="text-sm text-blue-700">
                <span class="font-bold">Info:</span> Maksimal 4 produk akan ditampilkan di landing page. Produk diurutkan berdasarkan "Urutan Unggulan".
            </p>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    {{-- Featured Products Summary --}}
    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
            <div class="text-2xl font-bold text-gray-900">{{ $featuredCount }}</div>
            <div class="text-sm text-gray-500">Produk Unggulan</div>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
            <div class="text-2xl font-bold text-green-600">{{ min($featuredCount, 4) }}</div>
            <div class="text-sm text-gray-500">Ditampilkan di Landing</div>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 md:col-span-2 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="text-sm font-medium text-gray-900">Tips</div>
                <div class="text-xs text-gray-500">Gunakan tombol panah untuk mengatur urutan tampilan</div>
            </div>
        </div>
    </div>

    {{-- Search --}}
    <div class="mb-6">
        <div class="relative">
            <input type="text" 
                   wire:model.live="search"
                   placeholder="Cari produk..."
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-black focus:border-brand-black">
            <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Products Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status Unggulan</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Urutan</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50 {{ $product->is_featured ? 'bg-yellow-50/50' : '' }}">
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                @if($product->gallery->first())
                                    <img src="{{ asset('storage/' . $product->gallery->first()->image_url) }}" 
                                         alt="{{ $product->name }}"
                                         class="w-12 h-12 rounded-lg object-cover">
                                @else
                                    <div class="w-12 h-12 rounded-lg bg-gray-200 flex items-center justify-center text-lg font-bold text-gray-400">
                                        {{ substr($product->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                    <div class="text-xs text-gray-500">ID: {{ $product->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-600">
                            {{ $product->category->name ?? '-' }}
                        </td>
                        <td class="px-4 py-4 text-center">
                            <button type="button" wire:click="toggleFeatured({{ $product->id }})"
                                    wire:loading.attr="disabled"
                                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm font-medium transition-colors cursor-pointer touch-manipulation
                                        {{ $product->is_featured 
                                            ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' 
                                            : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                @if($product->is_featured)
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    Unggulan
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Jadikan Unggulan
                                @endif
                            </button>
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if($product->is_featured)
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" wire:click="moveUp({{ $product->id }})"
                                            wire:loading.attr="disabled"
                                            class="p-1 rounded hover:bg-gray-200 text-gray-600 cursor-pointer touch-manipulation">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                        </svg>
                                    </button>
                                    <span class="font-mono text-sm font-medium text-gray-900 w-8">{{ $product->featured_order ?? 1 }}</span>
                                    <button type="button" wire:click="moveDown({{ $product->id }})"
                                            wire:loading.attr="disabled"
                                            class="p-1 rounded hover:bg-gray-200 text-gray-600 cursor-pointer touch-manipulation">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if($product->is_featured)
                                <button type="button" wire:click="openOrderModal({{ $product->id }})"
                                        class="text-blue-600 hover:text-blue-800 text-sm font-medium cursor-pointer touch-manipulation">
                                    Edit Urutan
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                            Tidak ada produk ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $products->links() }}
    </div>

    {{-- Order Modal --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Urutan Unggulan</h3>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Produk</label>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <span class="font-medium text-gray-900">{{ $editingProduct?->name }}</span>
                            </div>
                        </div>
                        <div>
                            <label for="order" class="block text-sm font-medium text-gray-700 mb-1">Urutan (1-10)</label>
                            <input type="number" 
                                   wire:model="featuredOrder"
                                   id="order"
                                   min="1"
                                   max="10"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-brand-black focus:border-brand-black sm:text-sm">
                            @error('featuredOrder') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="saveOrder"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-black text-base font-medium text-white hover:bg-gray-800 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm cursor-pointer touch-manipulation">
                            Simpan
                        </button>
                        <button type="button" wire:click="closeModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm cursor-pointer touch-manipulation">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
