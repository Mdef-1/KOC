<div class="p-6 bg-white rounded-lg shadow">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Product Gallery</h2>
        <button wire:click="create"
            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
            Add Image
        </button>
    </div>

    {{-- Flash --}}
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
        <div class="relative w-full sm:w-96 group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by product name.."
                class="block w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 sm:text-sm transition-all">

            @if($search)
                <button wire:click="$set('search', '')"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                    title="Clear search">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            @endif
        </div>

        <div class="flex items-center space-x-2 text-sm text-gray-500">
            @if($search)
                <span>Showing results for "<span class="font-medium text-gray-900">{{ $search }}</span>"</span>
            @endif
        </div>
    </div>

    {{-- Gallery Grid --}}
    @if($galleries->count())
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
            @foreach($galleries as $gallery)
                <div class="relative border rounded-lg overflow-hidden group">
                    {{-- Image --}}
                    <img src="{{ asset('storage/' . $gallery->image_url) }}"
                        class="w-full h-40 object-cover">

                    {{-- Primary badge --}}
                    @if($gallery->is_primary)
                        <span
                            class="absolute top-2 left-2 bg-green-600 text-white text-xs px-2 py-1 rounded">
                            Primary
                        </span>
                    @endif

                    {{-- Info --}}
                    <div class="p-3 text-sm">
                        <div class="font-medium truncate">
                            {{ $gallery->product->name ?? 'Unknown Product' }}
                        </div>
                        <div class="text-gray-500 text-xs">
                            Sort: {{ $gallery->sort_order }}
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div
                        class="absolute inset-0 bg-black/60 flex items-center justify-center gap-3 opacity-0 group-hover:opacity-100 transition">
                        <button wire:click="edit({{ $gallery->id }})"
                            class="px-3 py-1 text-xs bg-white rounded">
                            Edit
                        </button>
                        <button wire:click="delete({{ $gallery->id }})"
                            class="px-3 py-1 text-xs bg-red-600 text-white rounded">
                            Delete
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $galleries->links() }}
        </div>
    @else
        <div class="text-center py-12 text-gray-500">
            No gallery images found.
        </div>
    @endif

    {{-- Modal --}}
    @if($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div class="bg-white w-full max-w-lg rounded-lg shadow-lg">
                {{-- Header --}}
                <div class="px-6 py-4 border-b flex justify-between items-center">
                    <h3 class="text-lg font-semibold">
                        {{ $selected_id ? 'Edit Image' : 'Add Image' }}
                    </h3>
                    <button wire:click="$set('isOpen', false)" class="text-gray-400 hover:text-gray-600">
                        ✕
                    </button>
                </div>

                <form wire:submit.prevent="store">
                    <div class="px-6 py-4 space-y-4">
                        {{-- Product --}}
                        <div>
                            <label class="block text-sm font-medium">Product</label>
                            <select wire:model.defer="product_id"
                                class="mt-1 w-full border rounded-md px-3 py-2">
                                <option value="">-- Select Product --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Image --}}
                        <div>
                            <label class="block text-sm font-medium">
                                Image {{ $selected_id ? '(optional)' : '*' }}
                            </label>
                            <input type="file" wire:model="image"
                                class="mt-1 w-full text-sm">
                            @error('image')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            {{-- Preview --}}
                            @if($image)
                                <img src="{{ $image->temporaryUrl() }}"
                                    class="mt-2 h-32 rounded object-cover">
                            @endif
                        </div>

                        {{-- Sort Order --}}
                        <div>
                            <label class="block text-sm font-medium">Sort Order</label>
                            <input type="number" wire:model.defer="sort_order"
                                class="mt-1 w-full border rounded-md px-3 py-2">
                        </div>

                        {{-- Primary --}}
                        <div class="flex items-center gap-2">
                            <input type="checkbox" wire:model.defer="is_primary"
                                class="rounded text-blue-600">
                            <label class="text-sm">Set as primary image</label>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="px-6 py-4 border-t bg-gray-50 flex justify-end gap-3">
                        <button type="button" wire:click="$set('isOpen', false)"
                            class="px-4 py-2 text-sm border rounded-md">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm text-white bg-blue-600 rounded-md hover:bg-blue-700">
                            {{ $selected_id ? 'Update' : 'Save' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
