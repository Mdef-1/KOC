<div class="p-6 bg-white rounded-lg shadow">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Inquiry Management</h2>
        <button type="button" wire:click="create"
            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 transition-colors cursor-pointer touch-manipulation">
            Add New Inquiry
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
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search inquiries..."
                class="block w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 sm:text-sm transition-all">
        </div>
    </div>

    {{-- Table Section --}}
    <div class="overflow-x-auto">
        @if(count($inquiries) > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Message</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($inquiries as $inquiry)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900">{{ $inquiry->customer_name }}</div>
                                            <div class="text-xs text-gray-500 italic">{{ $inquiry->customer_contact }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $inquiry->product->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600 italic">{{ Str::limit($inquiry->message, 40) ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @php
                                                $statusColors = [
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'contacted' => 'bg-blue-100 text-blue-800',
                                                    'deal' => 'bg-green-100 text-green-800',
                                                    'cancelled' => 'bg-red-100 text-red-800',
                                                ];
                                            @endphp
                                            <span
                                                class="px-2.5 py-1 rounded text-xs font-bold {{ $statusColors[$inquiry->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ strtoupper($inquiry->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right text-sm font-medium space-x-3">
                                            <button type="button" wire:click="edit({{ $inquiry->id }})"
                                                class="text-indigo-600 hover:text-indigo-900 cursor-pointer touch-manipulation">Edit</button>
                                            <button type="button" wire:click="delete({{ $inquiry->id }})"
                                                onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"
                                                class="text-red-600 hover:text-red-900 cursor-pointer touch-manipulation">Delete</button>
                                        </td>
                                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">{{ $inquiries->links() }}</div>
        @else
            <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed">No inquiries found</div>
        @endif
    </div>

    {{-- Modal --}}
    @if($isOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black/50 transition-opacity" wire:click="$set('isOpen', false)"></div>

                <div class="relative bg-white rounded-xl shadow-2xl max-w-lg w-full overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900">{{ $selected_id ? 'Edit' : 'Create' }} Inquiry</h3>
                    </div>

                    <form wire:submit.prevent="store">
                        <div class="px-6 py-6 space-y-4">
                            {{-- Customer Info Grid --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Customer Name <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" wire:model.defer="customer_name"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-3 py-2">
                                    @error('customer_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Contact Detail <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" wire:model.defer="customer_contact"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-3 py-2"
                                        placeholder="Email/WA">
                                    @error('customer_contact') <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- SELECT2 PRODUCT SELECTION --}}
                            <div wire:ignore x-data="{ 
                                        initSelect2() {
                                            let el = $(this.$refs.inquiryProd);
                                            el.select2({
                                                placeholder: 'Select Product',
                                                allowClear: true,
                                                width: '100%',
                                                dropdownParent: el.parent()
                                            }).on('change', (e) => {
                                                @this.set('product_id', e.target.value);
                                            });
                                        }
                                     }"
                                x-init="initSelect2(); $watch('$wire.product_id', value => $( $refs.inquiryProd ).val(value).trigger('change'))">

                                <label class="block text-sm font-medium text-gray-700">Product Inquiry <span
                                        class="text-red-500">*</span></label>
                                <select x-ref="inquiryProd"
                                    class="block w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all bg-white">
                                    <option value="">Select Product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ $product_id == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Message</label>
                                <textarea wire:model.defer="message" rows="3"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-3 py-2"></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <select wire:model.defer="status"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-3 py-2">
                                    <option value="pending">Pending</option>
                                    <option value="contacted">Contacted</option>
                                    <option value="deal">Deal</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3 rounded-b-xl border-t border-gray-100">
                            <button type="button" wire:click="$set('isOpen', false)"
                                class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors cursor-pointer touch-manipulation">Cancel</button>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 transition-colors cursor-pointer touch-manipulation">
                                <span wire:loading.remove>{{ $selected_id ? 'Save Changes' : 'Create Inquiry' }}</span>
                                <span wire:loading>Processing...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>