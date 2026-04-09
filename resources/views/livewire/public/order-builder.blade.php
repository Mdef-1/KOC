<div class="min-h-screen bg-gray-50">
    {{-- Progress Bar --}}
    <div class="bg-white border-b">
        <div class="max-w-4xl mx-auto px-4 py-4">
            <div class="flex items-center gap-2">
                @foreach([1 => 'Pilih Produk', 2 => 'Upload Desain', 3 => 'Input Size', 4 => 'Data Diri'] as $step => $label)
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium
                            {{ $currentStep > $step ? 'bg-green-500 text-white' : ($currentStep == $step ? 'bg-black text-white' : 'bg-gray-200 text-gray-600') }}">
                            @if($currentStep > $step)
                                ✓
                            @else
                                {{ $step }}
                            @endif
                        </div>
                        <span class="text-xs hidden sm:block {{ $currentStep == $step ? 'text-black font-medium' : 'text-gray-500' }}">{{ $label }}</span>
                    </div>
                    @if($step < 4)
                        <div class="flex-1 h-0.5 {{ $currentStep > $step ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="max-w-4xl mx-auto px-4 py-8">
        @if (session()->has('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-700 rounded-xl">
                {{ session('error') }}
            </div>
        @endif

        {{-- Step 1: Product Selection --}}
        @if($currentStep == 1)
            <div class="bg-white rounded-2xl shadow-sm border p-6">
                <h2 class="text-xl font-bold mb-6">1. Pilih Model Baju</h2>
                
                {{-- Category Selection --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Kategori</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($categories as $category)
                            <button wire:click="$set('selectedCategory', {{ $category->id }})"
                                    class="p-4 rounded-xl border-2 text-left transition-all
                                        {{ $selectedCategory == $category->id 
                                            ? 'border-black bg-black text-white' 
                                            : 'border-gray-200 hover:border-gray-300' }}">
                                <div class="font-medium">{{ $category->name }}</div>
                                <div class="text-xs {{ $selectedCategory == $category->id ? 'text-gray-300' : 'text-gray-500' }}">
                                    {{ $category->sizes->count() }} size tersedia
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Product Selection --}}
                @if($selectedCategory && $products->count())
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Produk</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            @foreach($products as $product)
                                <button wire:click="$set('selectedProduct', {{ $product->id }})"
                                        class="relative rounded-xl border-2 overflow-hidden transition-all
                                            {{ $selectedProduct == $product->id 
                                                ? 'border-black ring-2 ring-black/10' 
                                                : 'border-gray-200 hover:border-gray-300' }}">
                                    <div class="aspect-square bg-gray-100">
                                        @if($product->gallery->first())
                                            <img src="{{ asset('storage/' . $product->gallery->first()->image_url) }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-2xl font-bold text-gray-400">
                                                {{ substr($product->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-3 text-left">
                                        <div class="font-medium text-sm truncate">{{ $product->name }}</div>
                                        @if($selectedProduct == $product->id)
                                            <div class="text-xs text-green-600 font-medium mt-1">✓ Dipilih</div>
                                        @endif
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Selected Product Preview --}}
                @if($selectedProduct)
                    @php $product = $products->firstWhere('id', $selectedProduct); @endphp
                    @if($product)
                        <div class="bg-blue-50 rounded-xl p-4 mb-6">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-lg bg-white overflow-hidden">
                                    @if($product->gallery->first())
                                        <img src="{{ asset('storage/' . $product->gallery->first()->image_url) }}" 
                                             class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div>
                                    <div class="font-semibold">{{ $product->name }}</div>
                                    <div class="text-sm text-gray-600">{{ $product->category->name }}</div>
                                    <div class="text-sm text-blue-600 mt-1">
                                        Size tersedia: {{ implode(', ', $availableSizes) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                <div class="flex justify-end">
                    <button wire:click="nextStep" 
                            wire:loading.attr="disabled"
                            class="px-6 py-3 bg-black text-white rounded-xl font-medium hover:bg-gray-800 disabled:opacity-50">
                        Lanjutkan →
                    </button>
                </div>
            </div>
        @endif

        {{-- Step 2: Design Upload --}}
        @if($currentStep == 2)
            <div class="bg-white rounded-2xl shadow-sm border p-6">
                <h2 class="text-xl font-bold mb-6">2. Upload Desain</h2>
                
                <div class="mb-6">
                    <div class="flex gap-4 mb-6">
                        <button wire:click="$set('designType', 'custom')"
                                class="flex-1 p-4 rounded-xl border-2 text-center transition-all
                                    {{ $designType == 'custom' ? 'border-black bg-black text-white' : 'border-gray-200' }}">
                            <div class="text-2xl mb-2">🎨</div>
                            <div class="font-medium">Desain Baru</div>
                        </button>
                        <button wire:click="$set('designType', 'repeat')"
                                class="flex-1 p-4 rounded-xl border-2 text-center transition-all
                                    {{ $designType == 'repeat' ? 'border-black bg-black text-white' : 'border-gray-200' }}">
                            <div class="text-2xl mb-2">🔄</div>
                            <div class="font-medium">Pesan Ulang</div>
                        </button>
                    </div>

                    @if($designType == 'custom')
                        {{-- File Upload --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Upload File Desain</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:bg-gray-50 transition-colors">
                                @if($designFile)
                                    <div class="mb-4">
                                        <img src="{{ $designFile->temporaryUrl() }}" class="max-h-48 mx-auto rounded-lg">
                                    </div>
                                    <button wire:click="$set('designFile', null)" class="text-red-500 text-sm">Hapus & Upload Ulang</button>
                                @else
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <label class="cursor-pointer">
                                        <span class="text-blue-600 font-medium">Klik untuk upload</span>
                                        <input type="file" wire:model="designFile" accept="image/*" class="hidden">
                                    </label>
                                    <p class="text-xs text-gray-500 mt-2">PNG, JPG, PSD (Max 5MB)</p>
                                @endif
                            </div>
                            @error('designFile') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Design Notes --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan Tambahan</label>
                            <textarea wire:model="designNotes" rows="3" 
                                      class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-black/10 focus:border-black"
                                      placeholder="Contoh: Warna dominan hitam, logo di dada kiri..."></textarea>
                        </div>
                    @else
                        {{-- Repeat Order --}}
                        <div class="bg-yellow-50 rounded-xl p-6 text-center">
                            <div class="text-4xl mb-3">📋</div>
                            <p class="text-gray-700 mb-4">Silakan sebutkan nomor order sebelumnya di WhatsApp nanti.</p>
                            <p class="text-sm text-gray-500">Contoh: "Pesan ulang PO20240315-123 dengan size S:5, M:10..."</p>
                        </div>
                    @endif
                </div>

                <div class="flex justify-between">
                    <button wire:click="prevStep" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50">
                        ← Kembali
                    </button>
                    <button wire:click="nextStep" 
                            wire:loading.attr="disabled"
                            class="px-6 py-3 bg-black text-white rounded-xl font-medium hover:bg-gray-800">
                        Lanjutkan →
                    </button>
                </div>
            </div>
        @endif

        {{-- Step 3: Size Grid --}}
        @if($currentStep == 3)
            <div class="bg-white rounded-2xl shadow-sm border p-6">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-xl font-bold">3. Input Quantity per Size</h2>
                    @if($totalQty > 0)
                        <button wire:click="resetSizeGrid" 
                                class="text-sm text-red-500 hover:text-red-700 font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset
                        </button>
                    @endif
                </div>
                <p class="text-gray-600 mb-6">Minimum order {{ \App\Livewire\Public\OrderBuilder::MOQ }} pcs</p>
                
                {{-- Size Quantity Indicator --}}
                @if($totalQty > 0)
                    <div class="mb-4">
                        <div class="text-sm text-gray-500 mb-2">Rincian Pesanan:</div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($availableSizes as $size)
                                @if(($sizeGrid[$size] ?? 0) > 0)
                                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 border border-blue-200 rounded-lg">
                                        <span class="font-bold text-blue-800">{{ $size }}</span>
                                        <span class="text-blue-600">× {{ $sizeGrid[$size] }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
                
                {{-- MOQ Status --}}
                <div class="mb-6 p-4 rounded-xl {{ $moqMet ? 'bg-green-50 border border-green-200' : 'bg-yellow-50 border border-yellow-200' }}">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm {{ $moqMet ? 'text-green-700' : 'text-yellow-700' }}">Total Quantity</div>
                            <div class="text-3xl font-bold {{ $moqMet ? 'text-green-600' : 'text-yellow-600' }}">{{ $totalQty }} pcs</div>
                        </div>
                        <div class="text-right">
                            @if($moqMet)
                                <div class="text-green-600 font-medium">✓ MOQ Tercapai</div>
                            @else
                                <div class="text-yellow-600 font-medium">Kurang {{ 12 - $totalQty }} pcs lagi</div>
                            @endif
                        </div>
                    </div>
                    
                    {{-- Progress Bar --}}
                    <div class="mt-3 h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full {{ $moqMet ? 'bg-green-500' : 'bg-yellow-500' }} transition-all"
                             style="width: {{ min(($totalQty / 12) * 100, 100) }}%"></div>
                    </div>
                </div>

                {{-- Size Grid --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mb-6">
                    @foreach($availableSizes as $size)
                        <div class="bg-gray-50 rounded-xl p-4 {{ ($sizeGrid[$size] ?? 0) > 0 ? 'ring-2 ring-blue-500 bg-blue-50/50' : '' }}">
                            <div class="text-center mb-3">
                                <div class="text-2xl font-bold {{ ($sizeGrid[$size] ?? 0) > 0 ? 'text-blue-600' : '' }}">{{ $size }}</div>
                                <div class="text-xs text-gray-500">Size</div>
                            </div>
                            <div class="flex items-center justify-center gap-2">
                                <button wire:click="decrementSize('{{ $size }}')" 
                                        class="w-8 h-8 rounded-lg bg-white border border-gray-300 flex items-center justify-center hover:bg-gray-100 {{ ($sizeGrid[$size] ?? 0) > 0 ? 'border-blue-300' : '' }}">
                                    −
                                </button>
                                <input type="number" 
                                       wire:model="sizeGrid.{{ $size }}"
                                       wire:change="calculateTotal"
                                       class="w-14 text-center font-bold border-0 bg-transparent text-lg {{ ($sizeGrid[$size] ?? 0) > 0 ? 'text-blue-600' : '' }}"
                                       min="0" value="{{ $sizeGrid[$size] ?? 0 }}">
                                <button wire:click="incrementSize('{{ $size }}')"
                                        class="w-8 h-8 rounded-lg bg-white border border-gray-300 flex items-center justify-center hover:bg-gray-100 {{ ($sizeGrid[$size] ?? 0) > 0 ? 'border-blue-300' : '' }}">
                                    +
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Quick Fill --}}
                <div class="flex flex-wrap gap-2 mb-6">
                    @foreach($availableSizes as $size)
                        @if(in_array($size, ['S', 'M', 'L', 'XL', '2XL', '3XL']))
                            <button wire:click="setSizeQty('{{ $size }}', 12)" 
                                    class="px-3 py-1.5 bg-gray-100 rounded-lg text-sm hover:bg-gray-200 transition-colors">
                                Isi 12 {{ $size }}
                            </button>
                        @endif
                    @endforeach
                </div>

                <div class="flex justify-between">
                    <button wire:click="prevStep" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50">
                        ← Kembali
                    </button>
                    <button wire:click="nextStep" 
                            wire:loading.attr="disabled"
                            @if(!$moqMet) disabled @endif
                            class="px-6 py-3 rounded-xl font-medium transition-colors
                                {{ $moqMet 
                                    ? 'bg-black text-white hover:bg-gray-800' 
                                    : 'bg-gray-200 text-gray-400 cursor-not-allowed' }}">
                        Lanjutkan →
                    </button>
                </div>
            </div>
        @endif

        {{-- Step 4: Customer Info --}}
        @if($currentStep == 4)
            <div class="bg-white rounded-2xl shadow-sm border p-6">
                <h2 class="text-xl font-bold mb-6">4. Data Pemesan</h2>
                
                <div class="space-y-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                        <input type="text" wire:model="customerName" 
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-black/10 focus:border-black"
                               placeholder="Nama Anda">
                        @error('customerName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">WhatsApp / No. HP *</label>
                        <input type="text" wire:model="customerContact" 
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-black/10 focus:border-black"
                               placeholder="081234567890">
                        @error('customerContact') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap *</label>
                        <textarea wire:model="customerAddress" rows="3"
                                  class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-black/10 focus:border-black"
                                  placeholder="Alamat pengiriman lengkap..."></textarea>
                        @error('customerAddress') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Order Summary Preview --}}
                @if($selectedProduct)
                    @php $product = \App\Models\Product::find($selectedProduct); @endphp
                    <div class="bg-gray-50 rounded-xl p-4 mb-6">
                        <h3 class="font-semibold mb-3">Ringkasan Pesanan:</h3>
                        <div class="text-sm space-y-1">
                            <div><span class="text-gray-600">Produk:</span> {{ $product->name }}</div>
                            <div><span class="text-gray-600">Total:</span> {{ $totalQty }} pcs</div>
                            <div><span class="text-gray-600">Size:</span> 
                                @foreach($sizeGrid as $size => $qty)
                                    @if($qty > 0) {{ $size }}:{{ $qty }} @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <div class="flex justify-between">
                    <button wire:click="prevStep" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50">
                        ← Kembali
                    </button>
                    <button wire:click="submitOrder" 
                            wire:loading.attr="disabled"
                            class="px-6 py-3 bg-green-600 text-white rounded-xl font-medium hover:bg-green-700 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                            <path d="M12 0C5.373 0 0 5.373 0 12c0 2.111.547 4.099 1.504 5.828L0 24l6.335-1.652A11.94 11.94 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.82c-1.87 0-3.63-.5-5.14-1.38l-.36-.22-3.76.98.99-3.65-.24-.38A9.82 9.82 0 012.18 12c0-5.42 4.4-9.82 9.82-9.82 5.42 0 9.82 4.4 9.82 9.82 0 5.42-4.4 9.82-9.82 9.82z"/>
                        </svg>
                        Kirim ke WhatsApp
                    </button>
                </div>
            </div>
        @endif
    </div>

    {{-- Success Modal --}}
    @if($showSummary)
        <div class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4" wire:click.self="resetForm">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 text-center" x-data @open-whatsapp.window="setTimeout(() => window.open($event.detail.url, '_blank'), 500)">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-2">Pesanan Tersimpan!</h3>
                <p class="text-gray-600 mb-4">Order Number: <span class="font-mono font-bold">{{ $orderNumber }}</span></p>
                <p class="text-sm text-gray-500 mb-6">Kami akan membuka WhatsApp dengan detail pesanan Anda. Silakan kirimkan juga file desain (jika ada).</p>
                <button wire:click="resetForm" class="px-6 py-3 bg-black text-white rounded-xl font-medium hover:bg-gray-800">
                    Buat Pesanan Baru
                </button>
            </div>
        </div>
    @endif
</div>
