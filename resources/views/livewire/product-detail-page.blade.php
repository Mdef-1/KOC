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

            {{-- SKU Badge --}}
            <div class="hidden md:block">
                <div class="px-4 py-2 bg-black/80 backdrop-blur-md rounded-full text-white text-[10px] font-bold tracking-widest uppercase border border-white/20">
                    SKU: {{ $inventory->sku ?? 'PROD-' . $inventory->product->id }}
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-xl overflow-hidden border border-slate-100">
            <div class="flex flex-col lg:flex-row">
                {{-- Left: Image Gallery --}}
                <div class="w-full lg:w-[48%] p-4 sm:p-8" x-data="{ 
                    activeSlide: 0, 
                    slides: {{ $inventory->product->gallery->map(fn($item) => asset('storage/' . $item->image_url)) }},
                    next() { this.activeSlide = (this.activeSlide + 1) % this.slides.length },
                    prev() { this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length }
                }"
                    x-init="activeSlide = {{ $inventory->product->gallery->values()->search(fn($item) => $item->is_primary) ?: 0 }}">

                    <div class="relative aspect-[3/4] rounded-[2rem] overflow-hidden bg-slate-100 shadow-inner group">
                        <template x-for="(slide, index) in slides" :key="index">
                            <div x-show="activeSlide === index" x-transition:enter="transition ease-out duration-500"
                                x-transition:enter-start="opacity-0 scale-105"
                                x-transition:enter-end="opacity-100 scale-100" class="absolute inset-0">
                                <img :src="slide" class="w-full h-full object-cover"
                                    alt="{{ $inventory->product->name }}">
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
                    <div class="mb-6">
                        <span class="inline-block px-4 py-1.5 bg-black/5 text-black text-[10px] font-bold uppercase tracking-[0.2em] rounded-lg">
                            Stok Tersedia: {{ $inventory->stock }}
                        </span>
                    </div>

                    <h1 class="text-4xl lg:text-5xl xl:text-6xl font-black text-slate-900 mb-6 tracking-tight leading-[1.1] uppercase">
                        {{ $inventory->product->name }}
                    </h1>

                    <div class="flex items-center gap-4 mb-8">
                        <div class="px-6 py-2 bg-black rounded-2xl shadow-lg">
                            <span class="text-2xl xl:text-3xl font-black text-white tracking-tight">
                                Rp{{ number_format($inventory->price, 0, ',', '.') }}
                            </span>
                        </div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Harga Terbaik</span>
                    </div>

                    <div class="space-y-4 mb-10">
                        <div class="flex items-center gap-3">
                            <span class="h-0.5 w-6 bg-black rounded-full"></span>
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em]">Deskripsi Produk</h4>
                        </div>
                        <p class="text-gray-500 leading-relaxed text-base">
                            {{ $inventory->product->description ?? 'Nikmati kualitas premium dari koleksi terbaik kami yang dirancang untuk kenyamanan maksimal.' }}
                        </p>
                    </div>

                    @php
                        $waMessage = "Halo Admin, saya tertarik dengan produk *" . $inventory->product->name . "* (SKU: " . $inventory->sku . "). Apakah masih tersedia?";
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
                        <a href="{{ $waLink }}&text={{ urlencode('Halo, saya ingin tanya-tanya dulu tentang produk ' . $inventory->product->name) }}"
                            target="_blank"
                            class="flex-1 bg-white border-2 border-black text-black hover:bg-black hover:text-white flex items-center justify-center font-bold py-4 sm:py-5 rounded-2xl transition-all duration-300 active:scale-[0.98] text-sm sm:text-base">
                            KONSULTASI
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <p class="text-center mt-16 text-gray-400 text-xs tracking-[0.3em] font-bold uppercase">
            Official Store • Quality Control Passed • Fast Response
        </p>
    </div>
</div>