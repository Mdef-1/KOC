<div class="min-h-screen bg-gray-50">

    {{-- Main Content --}}
    <div class="max-w-2xl mx-auto px-4 py-12">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold mb-2">Lacak Pesanan</h1>
            <p class="text-gray-600">Cek status PO/Custom order Anda</p>
        </div>

        {{-- Search Form --}}
        @if(!$searched || $error)
            <div class="bg-white rounded-2xl shadow-sm border p-6">
                @if($error)
                    <div class="mb-4 p-4 bg-red-50 text-red-700 rounded-xl">
                        {{ $error }}
                    </div>
                @endif

                <form wire:submit="search" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Order</label>
                        <input type="text" wire:model="orderNumber" 
                               placeholder="Contoh: PO20240331-123"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-black/10 focus:border-black uppercase">
                        @error('orderNumber') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor WhatsApp</label>
                        <input type="text" wire:model="phoneNumber" 
                               placeholder="081234567890"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-black/10 focus:border-black">
                        @error('phoneNumber') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" wire:loading.attr="disabled"
                            class="w-full py-3 bg-black text-white rounded-xl font-medium hover:bg-gray-800 disabled:opacity-50">
                        <span wire:loading.remove>Cari Pesanan</span>
                        <span wire:loading>Mencari...</span>
                    </button>
                </form>

                <div class="mt-6 pt-6 border-t text-center">
                    <p class="text-sm text-gray-500">Belum punya nomor order?</p>
                    <a href="{{ route('order.builder') }}" class="text-blue-600 font-medium hover:underline">
                        Buat Pesanan Baru →
                    </a>
                </div>
            </div>
        @endif

        {{-- Order Result --}}
        @if($order)
            <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
                {{-- Header --}}
                <div class="bg-green-50 p-6 border-b">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm text-green-700 mb-1">Nomor Order</div>
                            <div class="text-2xl font-mono font-bold text-green-800">{{ $order['number'] }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-green-700 mb-1">Status</div>
                            <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                {{ $order['status'] === 'completed' ? 'bg-green-500 text-white' : 'bg-yellow-500 text-white' }}">
                                {{ ucfirst($order['status']) }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Progress Steps --}}
                <div class="p-6 border-b">
                    <div class="flex items-center justify-between relative">
                        @php
                            $steps = [
                                1 => ['icon' => '📋', 'label' => 'Order Masuk'],
                                2 => ['icon' => '✅', 'label' => 'Dikonfirmasi'],
                                3 => ['icon' => '🎨', 'label' => 'Printing'],
                                4 => ['icon' => '🧵', 'label' => 'Jahit'],
                                5 => ['icon' => '📦', 'label' => 'Packing'],
                                6 => ['icon' => '🚚', 'label' => 'Dikirim'],
                            ];
                            $currentStep = $this->getStatusStep();
                        @endphp

                        @foreach($steps as $step => $data)
                            <div class="flex flex-col items-center relative z-10">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg
                                    {{ $step <= $currentStep ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-500' }}">
                                    {{ $step < $currentStep ? '✓' : $data['icon'] }}
                                </div>
                                <span class="text-xs mt-2 text-center {{ $step <= $currentStep ? 'text-green-700 font-medium' : 'text-gray-400' }}">
                                    {{ $data['label'] }}
                                </span>
                            </div>
                            @if($step < 6)
                                <div class="flex-1 h-1 mx-2 {{ $step < $currentStep ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                            @endif
                        @endforeach
                    </div>
                </div>

                {{-- Order Details --}}
                <div class="p-6">
                    <div class="space-y-3">
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-600">Produk</span>
                            <span class="font-medium">{{ $order['product'] }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-600">Pemesan</span>
                            <span class="font-medium">{{ $order['customer'] }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-600">Total Quantity</span>
                            <span class="font-medium">{{ $order['total_quantity'] ?? 0 }} pcs</span>
                        </div>
                        @if(!empty($order['size_quantities']))
                            <div class="py-2 border-b">
                                <span class="text-gray-600 block mb-2">Rincian Size</span>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($order['size_quantities'] as $size => $qty)
                                        @if($qty > 0)
                                            <span class="px-3 py-1 bg-gray-100 rounded-lg text-sm">
                                                {{ $size }}: {{ $qty }} pcs
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-600">Tanggal Order</span>
                            <span class="font-medium">{{ $order['date']?->format('d M Y') ?? '-' }}</span>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="mt-6 flex gap-3">
                        <a href="https://wa.me/6289513229597?text=Halo, saya mau tanya tentang order {{ $order['number'] }}" 
                           target="_blank"
                           class="flex-1 py-3 bg-green-600 text-white rounded-xl font-medium text-center hover:bg-green-700">
                            Chat Admin
                        </a>
                        <button wire:click="resetSearch"
                                class="px-6 py-3 border border-gray-300 rounded-xl hover:bg-gray-50">
                            Cari Lagi
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
