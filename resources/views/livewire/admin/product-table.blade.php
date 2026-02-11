<div class="p-6 bg-white rounded-lg shadow">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Product Management</h2>
        <button wire:click="create"
            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 transition-colors">
            Add New Product
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
                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search products..."
                class="block w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 sm:text-sm transition-all">
        </div>
    </div>

    {{-- Table Section --}}
    <div class="overflow-x-auto">
        @if(count($products) > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($products as $product)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                        <div class="text-xs text-gray-400 font-mono">{{ $product->slug }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $product->category->name ?? 'Uncategorized' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 inline-flex text-xs font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium space-x-3">
                                <button wire:click="edit({{ $product->id }})" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                <button wire:click="delete({{ $product->id }})" onclick="confirm('Delete this product?') || event.stopImmediatePropagation()" class="text-red-600 hover:text-red-900">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">{{ $products->links() }}</div>
        @else
            <div class="text-center py-12 text-gray-500">No products found.</div>
        @endif
    </div>

    {{-- Modal --}}
    @if($isOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black/50 transition-opacity" wire:click="$set('isOpen', false)"></div>

                <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full overflow-hidden">
                    <form wire:submit.prevent="store">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="text-lg font-bold text-gray-900">{{ $selected_id ? 'Edit' : 'Create' }} Product</h3>
                        </div>

                        <div class="px-6 py-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                            
                            {{-- SELECT2 CATEGORY --}}
                            <div class="sm:col-span-2" wire:ignore 
                                 x-data="{ 
                                    initSelect2() {
                                        let el = $(this.$refs.catSelect);
                                        el.select2({
                                            placeholder: 'Select Category',
                                            allowClear: true,
                                            width: '100%',
                                            dropdownParent: el.parent()
                                        }).on('change', (e) => {
                                            @this.set('category_id', e.target.value);
                                        });
                                    }
                                 }" 
                                 x-init="initSelect2(); $watch('$wire.category_id', value => $( $refs.catSelect ).val(value).trigger('change'))">
                                
                                <label class="block text-sm font-medium text-gray-700">Category <span class="text-red-500">*</span></label>
                                <select x-ref="catSelect" class="mt-1 block w-full border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            {{-- Name Input --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Name <span class="text-red-500">*</span></label>
                                <input type="text" wire:model.live="name" 
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-3 py-2">
                                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            {{-- Slug Input (Readonly) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Slug</label>
                                <input type="text" wire:model.defer="slug" readonly 
                                    class="mt-1 block w-full border border-gray-300 rounded-md bg-gray-50 text-sm text-gray-500 cursor-not-allowed px-3 py-2">
                            </div>

                            {{-- Description --}}
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea wire:model.defer="description" rows="3" 
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-3 py-2"></textarea>
                            </div>

                            {{-- Image Upload --}}
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Product Image</label>
                                <div class="mt-2 flex items-center space-x-4">
                                    @if ($new_image)
                                        <img src="{{ $new_image->temporaryUrl() }}" class="h-16 w-16 object-cover rounded-md border border-gray-200">
                                    @elseif ($image_url)
                                        <img src="{{ asset('storage/' . $image_url) }}" class="h-16 w-16 object-cover rounded-md border border-gray-200">
                                    @endif
                                    <input type="file" wire:model="new_image" 
                                        class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                                </div>
                                <div wire:loading wire:target="new_image" class="text-xs text-indigo-600 mt-1 italic">Uploading preview...</div>
                                @error('new_image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            {{-- Active Status --}}
                            <div class="flex items-center">
                                <input type="checkbox" wire:model.defer="is_active" id="is_active_check" 
                                    class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <label for="is_active_check" class="ml-2 block text-sm text-gray-900 font-medium cursor-pointer">Set as Active</label>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3 rounded-b-lg border-t border-gray-100">
                            <button type="button" wire:click="$set('isOpen', false)" 
                                class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">Cancel</button>
                            <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 transition-colors">
                                {{ $selected_id ? 'Update' : 'Create' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>