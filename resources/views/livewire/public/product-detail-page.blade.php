<div class="bg-[#f8f9fa] min-h-screen py-12 font-sans antialiased">
    <div class="container mx-auto px-4 sm:px-6 max-w-7xl">
        {{-- Navigation Header --}}
        <div class="flex items-center justify-between mb-10">
            {{-- Back Button --}}
            <a href="{{ url()->previous() }}" wire:navigate
                class="group flex items-center gap-3 transition-all duration-300">
                <div class="relative">
                    <div class="relative bg-white/80 backdrop-blur-md p-3 rounded-2xl shadow-sm border border-slate-200 group-hover:border-black transition-all duration-300 active:scale-90">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 transform group-hover:-translate-x-1 transition-transform duration-300"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </div>
                </div>
                <div class="flex flex-col">
                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-[0.2em]">Back to</span>
                    <span class="text-sm font-black text-slate-900 group-hover:text-black transition-colors duration-300 uppercase tracking-wider">Collection</span>
                </div>
            </a>

            {{-- Category Badge --}}
            <div class="hidden md:block">
                <div class="px-4 py-2 bg-black/80 backdrop-blur-md rounded-full text-white text-[10px] font-bold tracking-widest uppercase border border-white/20">
                    {{ $product->category->name ?? 'KOC Product' }}
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-xl overflow-hidden border border-slate-100">
            <div class="flex flex-col lg:flex-row">
                {{-- Left: Image Gallery --}}
                <div class="w-full lg:w-[48%] p-4 sm:p-8" x-data="{ 
                    activeSlide: 0, 
                    slides: {{ $product->gallery->map(fn($item) => asset('storage/' . $item->image_url)) }},
                    next() { this.activeSlide = (this.activeSlide + 1) % this.slides.length },
                    prev() { this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length }
                }"
                    x-init="activeSlide = {{ $product->gallery->values()->search(fn($item) => $item->is_primary) ?: 0 }}">

                    <div class="relative aspect-[3/4] rounded-[2rem] overflow-hidden bg-slate-100 shadow-inner group">
                        <template x-for="(slide, index) in slides" :key="index">
                            <div x-show="activeSlide === index" x-transition:enter="transition ease-out duration-500"
                                x-transition:enter-start="opacity-0 scale-105"
                                x-transition:enter-end="opacity-100 scale-100" class="absolute inset-0">
                                <img :src="slide" class="w-full h-full object-cover"
                                    alt="{{ $product->name }}">
                            </div>
                        </template>

                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent pointer-events-none">
                        </div>

                        {{-- Arrows --}}
                        <template x-if="slides.length > 1">
                            <div class="absolute inset-0 flex items-center justify-between px-6 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button @click="prev()"
                                    class="bg-white/90 backdrop-blur-sm p-3 rounded-full shadow-lg hover:bg-white transition-all active:scale-90">
                                    <svg class="h-6 w-6 text-slate-800" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <button @click="next()"
                                    class="bg-white/90 backdrop-blur-sm p-3 rounded-full shadow-lg hover:bg-white transition-all active:scale-90">
                                    <svg class="h-6 w-6 text-slate-800" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    {{-- Thumbnails --}}
                    <template x-if="slides.length > 1">
                        <div class="flex gap-3 mt-6 overflow-x-auto pb-2 no-scrollbar">
                            <template x-for="(slide, index) in slides" :key="index">
                                <button @click="activeSlide = index"
                                    :class="activeSlide === index ? 'ring-2 ring-black scale-95' : 'opacity-50 hover:opacity-100'"
                                    class="relative w-16 h-16 flex-shrink-0 rounded-xl overflow-hidden transition-all duration-300 bg-slate-200">
                                    <img :src="slide" class="w-full h-full object-cover">
                                </button>
                            </template>
                        </div>
                    </template>
                </div>

                {{-- Right: Content --}}
                <div class="w-full lg:w-[52%] p-8 lg:pr-16 lg:pl-8 xl:pl-16 flex flex-col justify-center">
                    {{-- Product Name --}}
                    <h1 class="text-4xl lg:text-5xl xl:text-6xl font-black text-slate-900 mb-6 tracking-tight leading-[1.1] uppercase">
                        {{ $product->name }}
                    </h1>

                    {{-- Size Selection --}}
                    @if($inventories->count() > 0)
                        <div class="mb-6">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="h-0.5 w-6 bg-black rounded-full"></span>
                                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em]">Pilih Ukuran</h4>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($inventories as $inv)
                                    <button wire:click="selectSize({{ $inv->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="selectSize({{ $inv->id }})"
                                        class="relative px-4 py-3 rounded-xl border-2 transition-all duration-200 text-sm font-bold
                                            {{ $selectedInventoryId === $inv->id 
                                                ? 'border-black bg-black text-white' 
                                                : ($inv->stock > 0 
                                                    ? 'border-slate-200 bg-white text-slate-700 hover:border-slate-400' 
                                                    : 'border-slate-100 bg-slate-50 text-slate-300 cursor-not-allowed') }}">
                                        {{ $inv->size->label ?? 'N/A' }}
                                        @if($inv->stock <= 0)
                                            <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full" title="Stok Habis"></span>
                                        @elseif($inv->stock < 5)
                                            <span class="absolute -top-1 -right-1 w-3 h-3 bg-yellow-500 rounded-full" title="Stok Menipis"></span>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                            <p class="text-xs text-gray-400 mt-2">
                                <span class="inline-block w-2 h-2 bg-green-500 rounded-full mr-1"></span> Tersedia
                                <span class="inline-block w-2 h-2 bg-yellow-500 rounded-full ml-3 mr-1"></span> Stok Menipis
                                <span class="inline-block w-2 h-2 bg-red-500 rounded-full ml-3 mr-1"></span> Habis
                            </p>
                        </div>

                        {{-- Selected Size Info --}}
                        @if($selectedSize)
                            <div class="mb-6 grid grid-cols-2 gap-3">
                                <div class="bg-slate-50 rounded-xl p-3 border border-slate-200">
                                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Stok</span>
                                    <span class="text-sm font-bold {{ $selectedSize->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $selectedSize->stock > 0 ? $selectedSize->stock . ' pcs' : 'Habis' }}
                                    </span>
                                </div>
                                <div class="bg-slate-50 rounded-xl p-3 border border-slate-200">
                                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Dimensi</span>
                                    <span class="text-sm font-bold text-slate-900">{{ $selectedSize->size->width ?? 0 }} x {{ $selectedSize->size->length ?? 0 }}</span>
                                </div>
                            </div>

                            {{-- Price --}}
                            <div class="flex items-center gap-4 mb-8">
                                <div class="px-6 py-2 bg-black rounded-2xl shadow-lg">
                                    <span class="text-2xl xl:text-3xl font-black text-white tracking-tight">
                                        Rp{{ number_format($selectedSize->final_price, 0, ',', '.') }}
                                    </span>
                                </div>
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Harga Terbaik</span>
                            </div>
                        @endif
                    @else
                        <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                            <p class="text-yellow-700 text-sm font-medium">Produk ini belum tersedia dalam inventory.</p>
                        </div>
                    @endif

                    {{-- Description --}}
                    <div class="space-y-4 mb-10">
                        <div class="flex items-center gap-3">
                            <span class="h-0.5 w-6 bg-black rounded-full"></span>
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em]">Deskripsi Produk</h4>
                        </div>
                        <p class="text-gray-500 leading-relaxed text-base">
                            {{ $product->description ?? 'Nikmati kualitas premium dari koleksi terbaik kami yang dirancang untuk kenyamanan maksimal.' }}
                        </p>
                    </div>

                    {{-- Action Buttons --}}
                    @if($selectedSize && $selectedSize->stock > 0)
                        @php
                            $waMessage = "Halo Admin, saya tertarik dengan produk *" . $product->name . "* (Ukuran: " . ($selectedSize->size->label ?? 'N/A') . "). Apakah masih tersedia?";
                            $waLink = "https://wa.me/6289513229597?text=" . urlencode($waMessage);
                        @endphp

                        <div class="flex flex-col sm:flex-row gap-4 mt-4">
                            <a href="{{ $waLink }}" target="_blank"
                                class="flex-[2] bg-black hover:bg-slate-800 text-white flex items-center justify-center gap-3 font-bold py-4 sm:py-5 rounded-2xl transition-all duration-300 shadow-lg hover:shadow-xl active:scale-[0.98] text-sm sm:text-base">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.438 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884 0 2.225.569 3.946 1.694 5.602l-1.082 3.95 4.077-1.071z"/>
                                </svg>
                                PESAN VIA WHATSAPP
                            </a>
                            <a href="{{ $waLink }}&text={{ urlencode('Halo, saya ingin tanya-tanya dulu tentang produk ' . $product->name) }}"
                                target="_blank"
                                class="flex-1 bg-white border-2 border-black text-black hover:bg-black hover:text-white flex items-center justify-center font-bold py-4 sm:py-5 rounded-2xl transition-all duration-300 active:scale-[0.98] text-sm sm:text-base">
                                KONSULTASI
                            </a>
                        </div>
                    @else
                        <div class="flex flex-col sm:flex-row gap-4 mt-4">
                            <button disabled
                                class="flex-[2] bg-slate-300 text-white flex items-center justify-center gap-3 font-bold py-4 sm:py-5 rounded-2xl cursor-not-allowed text-sm sm:text-base">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.438 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884 0 2.225.569 3.946 1.694 5.602l-1.082 3.95 4.077-1.071z"/>
                                </svg>
                                STOK HABIS
                            </button>
                            <a href="https://wa.me/6289513229597?text={{ urlencode('Halo, saya tertarik dengan produk ' . $product->name . ' tapi stok habis. Ada info restock?') }}"
                                target="_blank"
                                class="flex-1 bg-white border-2 border-slate-300 text-slate-400 hover:border-black hover:text-black flex items-center justify-center font-bold py-4 sm:py-5 rounded-2xl transition-all duration-300 active:scale-[0.98] text-sm sm:text-base">
                                Tanya Restock
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <p class="text-center mt-16 text-gray-400 text-xs tracking-[0.3em] font-bold uppercase">
            Official Store • Quality Control Passed • Fast Response
        </p>
    </div>
</div>