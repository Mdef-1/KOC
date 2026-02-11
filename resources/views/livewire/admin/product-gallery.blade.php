<div class="p-6 bg-white rounded-lg shadow">
    {{-- Header & Search --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Product Gallery</h2>
        <button wire:click="create"
            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition-colors">
            Add Image
        </button>
    </div>

    {{-- Flash Message --}}
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded relative">
            {{ session('message') }}
        </div>
    @endif

    {{-- Search Bar --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
        <div class="relative w-full sm:w-96 group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by product name.."
                class="block w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 sm:text-sm transition-all">
        </div>
    </div>

    {{-- Gallery Grid --}}
    @if($galleries->count())
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
            @foreach($galleries as $gallery)
                <div class="relative border rounded-lg overflow-hidden group">
                    <img src="{{ Str::startsWith($gallery->image_url, ['http://', 'https://'])
                    ? $gallery->image_url
                    : asset('storage/' . $gallery->image_url) }}" class="w-full h-40 object-cover">
                    @if($gallery->is_primary)
                        <span class="absolute top-2 left-2 bg-green-600 text-white text-xs px-2 py-1 rounded">Primary</span>
                    @endif

                    <div class="p-3 text-sm">
                        <div class="font-medium truncate">{{ $gallery->product->name ?? 'Unknown Product' }}</div>
                        <div class="text-gray-500 text-xs">Sort: {{ $gallery->sort_order }}</div>
                    </div>

                    <div
                        class="absolute inset-0 bg-black/60 flex items-center justify-center gap-3 opacity-0 group-hover:opacity-100 transition">
                        <button wire:click="edit({{ $gallery->id }})" class="px-3 py-1 text-xs bg-white rounded">Edit</button>
                        <button wire:click="delete({{ $gallery->id }})"
                            onclick="confirm('Hapus gambar ini?') || event.stopImmediatePropagation()"
                            class="px-3 py-1 text-xs bg-red-600 text-white rounded">Delete</button>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $galleries->links() }}</div>
    @else
        <div class="text-center py-12 text-gray-500">No gallery images found.</div>
    @endif

    {{-- Modal --}}
    @if($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div class="bg-white w-full max-w-lg rounded-lg shadow-lg">
                <div class="px-6 py-4 border-b flex justify-between items-center">
                    <h3 class="text-lg font-semibold">{{ $selected_id ? 'Edit Image' : 'Add Image' }}</h3>
                    <button wire:click="$set('isOpen', false)" class="text-gray-400 hover:text-gray-600">✕</button>
                </div>

                <form wire:submit.prevent="store">
                    <div class="px-6 py-4 space-y-4">

                        {{-- Select2 Product dengan Alpine.js --}}
                        <div class="sm:col-span-2" wire:ignore x-data="{ 
                                                                    initSelect() {
                                                                            let el = $(this.$refs.prodSelect);
                                                                            el.select2({
                                                                                placeholder: '-- Select Product --',
                                                                                allowClear: true,
                                                                                width: '100%',
                                                                                dropdownParent: el.parent()
                                                                        }).on('change', (e) => {
                                                                        @this.set('product_id', e.target.value);
                                                                    });
                                                                }
                                                            }"
                            x-init="initSelect(); $watch('$wire.product_id', value => $( $refs.prodSelect ).val(value).trigger('change'))">
                            <label class="block text-sm font-medium">Product <span class="text-red-500">*</span></label>
                            <select x-ref="prodSelect"
                                class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all bg-white">
                                <option value="">-- Select Product --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ $product_id == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Image Upload Section --}}
                        <div class="p-6 bg-white rounded-lg shadow border border-gray-200">
                            <label class="block text-sm font-medium text-center text-gray-700 mb-2">Upload Galeri
                                Produk</label>

                            <div wire:ignore x-data="{
                                                initDropzone() {
                                                    // Pastikan Dropzone tidak diinisialisasi dua kali
                                                    if (Dropzone.instances.length > 0) {
                                                        Dropzone.instances.forEach(dz => dz.destroy());
                                                    }

                                                    new Dropzone($refs.myDropzone, {
                                                        url: '#', 
                                                        autoProcessQueue: false,
                                                        paramName: 'file',
                                                        maxFilesize: 2,
                                                        acceptedFiles: 'image/*',
                                                        addRemoveLinks: true,
                                                        // Tambahkan baris ini untuk mencegah redirect
                                                        dictDefaultMessage: '', 
                                                        init: function() {
                                                            this.on('addedfile', function(file) {
                                                                @this.upload('new_images', file, (uploadedFilename) => {
                                                                    console.log('Uploaded: ' + uploadedFilename);
                                                                }, () => {
                                                                    alert('Upload failed');
                                                                });
                                                            });
                                                        }
                                                    });
                                                }
                                            }" x-init="initDropzone()">

                                <div x-ref="myDropzone"
                                    class="dropzone mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md cursor-pointer hover:bg-gray-50 transition">

                                    <div class="dz-message space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                            viewBox="0 0 48 48">
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <span class="relative font-medium text-blue-600">Klik atau seret gambar ke
                                                sini</span>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                    </div>
                                </div>
                            </div>

                            @error('new_images.*') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Sort Order & Primary --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium">Sort Order</label>
                                <input type="number" wire:model.defer="sort_order"
                                    class="mt-1 w-full border rounded-md px-3 py-2 text-sm">
                            </div>
                            <div class="flex items-center gap-2 pt-6">
                                <input type="checkbox" wire:model.defer="is_primary" id="is_primary"
                                    class="rounded text-blue-600">
                                <label for="is_primary" class="text-sm">Set as primary</label>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t bg-gray-50 flex justify-end gap-3 rounded-b-lg">
                        <button type="button" wire:click="$set('isOpen', false)"
                            class="px-4 py-2 text-sm border rounded-md bg-white">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-600 rounded-md hover:bg-blue-700">
                            {{ $selected_id ? 'Update' : 'Save' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>