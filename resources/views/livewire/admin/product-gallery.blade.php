<div class="p-6 max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Product Gallery</h1>
        <p class="mt-1 text-gray-600">Kelola foto produk dengan lebih efisien.</p>
    </div>

    {{-- Flash Messages --}}
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    {{-- Selected Product Gallery View --}}
    @if($selectedProduct)
        <div class="mb-4 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">{{ $selectedProduct->name }}</h2>
                <p class="text-sm text-gray-500">{{ $selectedProduct->category->name ?? 'No Category' }} • {{ count($images) }} gambar</p>
            </div>
            <button wire:click="closeProductGallery" 
                    class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Daftar Produk
            </button>
        </div>

        {{-- Upload Area --}}
        <div class="mb-6 p-6 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p class="mt-2 text-sm text-gray-600">
                    <label class="cursor-pointer text-blue-600 hover:text-blue-500 font-medium">
                        <span>Klik untuk upload</span>
                        <input type="file" wire:model="newImages" multiple accept="image/*" class="sr-only">
                    </label>
                    atau seret gambar ke sini
                </p>
                <p class="text-xs text-gray-500">PNG, JPG, GIF hingga 2MB (bisa multiple)</p>
                <div wire:loading wire:target="newImages" class="mt-2">
                    <span class="text-sm text-blue-600">Mengupload...</span>
                </div>
            </div>
        </div>

        {{-- Image Grid --}}
        @if(count($images) > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach($images as $index => $image)
                    <div class="group relative bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        {{-- Image --}}
                        <div class="aspect-square relative">
                            <img src="{{ asset('storage/' . $image['image_url']) }}" 
                                 alt="Product Image" 
                                 class="w-full h-full object-cover">
                            
                            {{-- Primary Badge --}}
                            @if($image['is_primary'])
                                <div class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded-full font-medium">
                                    Utama
                                </div>
                            @endif

                            {{-- Hover Actions --}}
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                <button wire:click="setPrimary({{ $image['id'] }})" 
                                        wire:loading.attr="disabled"
                                        @if($image['is_primary']) disabled @endif
                                        class="p-2 bg-white rounded-full hover:bg-gray-100 {{ $image['is_primary'] ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        title="Jadikan Utama">
                                    <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                </button>
                                <button wire:click="openEditModal({{ $image['id'] }})" 
                                        wire:loading.attr="disabled"
                                        class="p-2 bg-white rounded-full hover:bg-gray-100"
                                        title="Edit">
                                    <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button wire:click="deleteImage({{ $image['id'] }})" 
                                        wire:loading.attr="disabled"
                                        onclick="return confirm('Hapus gambar ini?')"
                                        class="p-2 bg-red-500 rounded-full hover:bg-red-600"
                                        title="Hapus">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Info & Reorder --}}
                        <div class="p-3 flex items-center justify-between">
                            <span class="text-xs font-mono text-gray-500">#{{ $image['sort_order'] }}</span>
                            <div class="flex gap-1">
                                <button wire:click="moveUp({{ $image['id'] }})" 
                                        wire:loading.attr="disabled"
                                        class="p-1 hover:bg-gray-100 rounded">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                    </svg>
                                </button>
                                <button wire:click="moveDown({{ $image['id'] }})" 
                                        wire:loading.attr="disabled"
                                        class="p-1 hover:bg-gray-100 rounded">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-gray-50 rounded-xl">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="mt-2 text-gray-500">Belum ada gambar untuk produk ini.</p>
                <p class="text-sm text-gray-400">Upload gambar di atas.</p>
            </div>
        @endif

    {{-- Product List View --}}
    @else
        {{-- Search --}}
        <div class="mb-6">
            <div class="relative max-w-md">
                <input type="text" 
                       wire:model.live.debounce.300ms="productSearch"
                       placeholder="Cari produk..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>

        {{-- Products Grid --}}
        @if($products->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($products as $product)
                    <div wire:click="selectProduct({{ $product->id }})"
                         class="group cursor-pointer bg-white rounded-xl border border-gray-200 p-4 hover:shadow-lg hover:border-blue-300 transition-all">
                        <div class="flex items-start gap-3">
                            {{-- Thumbnail --}}
                            <div class="w-16 h-16 rounded-lg bg-gray-100 flex-shrink-0 overflow-hidden">
                                @if($product->gallery->first())
                                    <img src="{{ asset('storage/' . $product->gallery->first()->image_url) }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-lg font-bold">
                                        {{ substr($product->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            
                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <h3 class="font-medium text-gray-900 truncate group-hover:text-blue-600">{{ $product->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $product->category->name ?? 'No Category' }}</p>
                                <div class="mt-1 flex items-center gap-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $product->gallery_count > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $product->gallery_count }} foto
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Action Hint --}}
                        <div class="mt-3 pt-3 border-t border-gray-100 text-center">
                            <span class="text-sm text-blue-600 font-medium group-hover:underline">
                                Kelola Gallery →
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-6">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p class="mt-2 text-gray-500">Tidak ada produk ditemukan.</p>
            </div>
        @endif
    @endif

    {{-- Edit Modal --}}
    @if($showEditModal)
        <div class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit Gambar</h3>
                    
                    @if($editingImage)
                        <div class="mb-4 aspect-video rounded-lg overflow-hidden bg-gray-100">
                            <img src="{{ asset('storage/' . $editingImage['image_url']) }}" 
                                 class="w-full h-full object-cover">
                        </div>
                    @endif
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                            <input type="number" 
                                   wire:model="editSortOrder"
                                   min="1"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <input type="checkbox" 
                                   wire:model="editIsPrimary"
                                   id="editIsPrimary"
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="editIsPrimary" class="text-sm text-gray-700">Jadikan gambar utama</label>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex gap-3">
                        <button wire:click="closeEditModal"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Batal
                        </button>
                        <button wire:click="saveEdit"
                                wire:loading.attr="disabled"
                                class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
