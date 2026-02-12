<div class="bg-[#f8f9fa] min-h-screen selection:bg-black selection:text-white">
    <section class="relative pt-32 pb-16 px-4 sm:px-6">
        <div class="max-w-7xl mx-auto text-center">
            <div class="inline-flex items-center gap-3 mb-6">
                <div class="w-8 sm:w-12 h-[1px] bg-gray-300"></div>
                <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-[0.4em] text-gray-400">
                    Our Collection
                </span>
                <div class="w-8 sm:w-12 h-[1px] bg-gray-300"></div>
            </div>

            <h1 class="text-5xl sm:text-7xl lg:text-8xl font-black leading-[0.9] tracking-tighter uppercase mb-8">
                Katalog <br /><span class="stroke-text">Produk</span>
            </h1>

            @if (session()->has('success'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
                    x-transition:leave="transition ease-in duration-500"
                    class="mb-8 max-w-md mx-auto rounded-2xl bg-black text-white px-8 py-4 text-[10px] font-bold uppercase tracking-widest shadow-2xl">
                    {{ session('success') }}
                </div>
            @endif

            <p class="text-gray-500 text-sm sm:text-base max-w-xl mx-auto leading-relaxed font-light italic">
                @if($search || $category)
                    Menampilkan hasil untuk <span class="text-black font-bold">"{{ $search ?: $category }}"</span>
                @else
                    "Kualitas yang bicara, kenyamanan yang terasa." — Eksplorasi koleksi terbaik untuk performa harianmu.
                @endif
            </p>
        </div>
    </section>

    <section class="pb-12 px-4">
        <div class="max-w-4xl mx-auto space-y-8">
            <div class="relative group">
                <input type="text" placeholder="CARI KOLEKSI SPESIFIK..." wire:model.live="search"
                    class="w-full px-8 py-5 rounded-2xl border-none bg-white shadow-sm focus:ring-2 focus:ring-gray-200 transition-all duration-500 text-xs font-bold tracking-widest placeholder:text-gray-300" />
                <div
                    class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-black transition-colors">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <div class="flex flex-wrap justify-center gap-2 sm:gap-3">
                <button wire:click="setCategory('')"
                    class="px-8 py-3 text-[10px] font-black uppercase tracking-[0.2em] rounded-full transition-all border {{ $category === '' ? 'bg-black text-white border-black shadow-xl shadow-black/20' : 'bg-white text-gray-400 border-gray-100 hover:border-black hover:text-black' }}">
                    All
                </button>
                @foreach($categories as $cat)
                    <button wire:key="cat-{{ $cat->id }}" wire:click="setCategory('{{ $cat->slug }}')"
                        class="px-8 py-3 text-[10px] font-black uppercase tracking-[0.2em] rounded-full transition-all border {{ $category === $cat->slug ? 'bg-black text-white border-black shadow-xl shadow-black/20' : 'bg-white text-gray-400 border-gray-100 hover:border-black hover:text-black' }}">
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    <section class="pb-32 px-4 sm:px-6">
        <div class="max-w-7xl mx-auto">
            <div wire:loading.flex class="justify-center items-center py-20">
                <div class="w-10 h-10 rounded-full border-[3px] border-gray-100 border-t-black animate-spin"></div>
            </div>

            <div wire:loading.remove>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-4 gap-y-12 sm:gap-x-8 sm:gap-y-20">
                    @forelse($products as $product)
                        @php
                            $galleryImage = optional($product->gallery->first())->image_url;
                            $img = $galleryImage ? (\Illuminate\Support\Str::startsWith($galleryImage, 'http')
                                ? $galleryImage
                                : asset('storage/' . preg_replace('/^storage\//', '', $galleryImage)))
                                : ($product->image_url ? (\Illuminate\Support\Str::startsWith($product->image_url, 'http')
                                    ? $product->image_url
                                    : asset('storage/' . preg_replace('/^storage\//', '', $product->image_url)))
                                    : null);
                        @endphp

                        <div class="group" wire:key="prod-{{ $product->id }}">
                            <div
                                class="relative aspect-[3/4] bg-white rounded-[1.5rem] sm:rounded-[2.5rem] mb-6 overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500">
                                @if($img)
                                    <img src="{{ $img }}" alt="{{ $product->name }}"
                                        class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-50 text-gray-200">
                                        <span
                                            class="font-black text-4xl uppercase -rotate-12">{{ substr($product->name, 0, 1) }}</span>
                                    </div>
                                @endif

                                <div
                                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-all duration-500 flex items-center justify-center p-4">
                                    <div class="space-y-2 w-full max-w-[140px]">
                                        <a href="{{ route('product.detail', ['id' => $product->id]) }}" wire:navigate
                                            class="block w-full py-3 bg-white text-black text-center rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-gray-100 transition-colors">
                                            Detail
                                        </a>
                                        <button wire:click="openInquiry({{ $product->id }})"
                                            class="block w-full py-3 bg-black text-white text-center rounded-xl text-[9px] font-black uppercase tracking-widest border border-white/20 hover:bg-gray-900 transition-colors">
                                            Inquiry
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="px-2">
                                <h4
                                    class="font-black text-base sm:text-lg tracking-tight uppercase italic leading-tight group-hover:text-gray-600 transition-colors">
                                    {{ $product->name }}
                                </h4>
                                <div class="flex justify-between items-center mt-2">
                                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-[0.1em]">
                                        {{ $product->category->name ?? 'Essentials' }}
                                    </p>
                                    <span class="font-bold text-sm sm:text-base">
                                        {{ $product->price ? 'Rp ' . number_format($product->price, 0, ',', '.') : 'Tanya Harga' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div
                            class="col-span-full text-center py-32 bg-white rounded-[3rem] shadow-sm border border-gray-100">
                            <p class="text-gray-400 font-black uppercase tracking-widest text-xs">Koleksi Tidak Ditemukan
                            </p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-24">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </section>

    <div x-data="{ open: @entangle('showInquiryModal') }" x-show="open"
        x-effect="document.body.style.overflow = open ? 'hidden' : 'auto'"
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" style="display: none;">

        <div class="absolute inset-0 bg-black/90 backdrop-blur-sm transition-opacity" x-show="open"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" wire:click="closeInquiry"></div>

        @if($selectedProduct)
            <div class="relative w-full max-w-4xl bg-white rounded-[2.5rem] sm:rounded-[3.5rem] shadow-2xl overflow-hidden"
                x-show="open" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0">

                <div class="grid md:grid-cols-2">
                    <div class="hidden md:block relative bg-gray-100">
                        @php
                            $modalImg = optional($selectedProduct->gallery->first())->image_url ?? $selectedProduct->image_url;
                            $modalImgUrl = $modalImg ? (\Illuminate\Support\Str::startsWith($modalImg, 'http')
                                ? $modalImg
                                : asset('storage/' . preg_replace('/^storage\//', '', $modalImg))) : null;
                        @endphp
                        <img src="{{ $modalImgUrl }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                        <div class="absolute bottom-12 left-12 text-white">
                            <h3 class="text-4xl font-black italic uppercase tracking-tighter mb-2">
                                {{ $selectedProduct->name }}</h3>
                            <p class="text-[10px] font-bold uppercase tracking-[0.3em] opacity-60">Verified Athletic Grade
                            </p>
                        </div>
                    </div>

                    <div class="p-10 sm:p-16">
                        <div class="flex justify-between items-start mb-12">
                            <div>
                                <h2 class="text-3xl font-black uppercase italic tracking-tighter leading-none">Inquiry</h2>
                                <div class="h-1 w-12 bg-black mt-4"></div>
                            </div>
                            <button wire:click="closeInquiry" class="text-gray-300 hover:text-black transition-colors">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <form wire:submit.prevent="submitInquiry" class="space-y-8">
                            <div class="relative group">
                                <input type="text" wire:model="customer_name" required
                                    class="w-full border-b-2 border-gray-100 focus:border-black transition-all outline-none py-3 text-sm font-bold uppercase tracking-widest peer bg-transparent">
                                <label
                                    class="absolute left-0 top-3 text-[9px] font-black uppercase tracking-[0.2em] text-gray-300 pointer-events-none transition-all peer-focus:-top-4 peer-focus:text-black peer-valid:-top-4 peer-valid:text-black">Nama
                                    Lengkap</label>
                            </div>

                            <div class="relative group">
                                <input type="text" wire:model="customer_contact" required
                                    class="w-full border-b-2 border-gray-100 focus:border-black transition-all outline-none py-3 text-sm font-bold uppercase tracking-widest peer bg-transparent">
                                <label
                                    class="absolute left-0 top-3 text-[9px] font-black uppercase tracking-[0.2em] text-gray-300 pointer-events-none transition-all peer-focus:-top-4 peer-focus:text-black peer-valid:-top-4 peer-valid:text-black">Kontak
                                    (WA/Email)</label>
                            </div>

                            <div class="relative group">
                                <textarea wire:model="message" rows="2" required
                                    class="w-full border-b-2 border-gray-100 focus:border-black transition-all outline-none py-3 text-sm font-bold uppercase tracking-widest peer bg-transparent resize-none"></textarea>
                                <label
                                    class="absolute left-0 top-3 text-[9px] font-black uppercase tracking-[0.2em] text-gray-300 pointer-events-none transition-all peer-focus:-top-4 peer-focus:text-black peer-valid:-top-4 peer-valid:text-black">Pesan
                                    Singkat</label>
                            </div>

                            <button type="submit" wire:loading.attr="disabled"
                                class="w-full py-5 bg-black text-white text-[10px] font-black uppercase tracking-[0.4em] rounded-2xl hover:bg-gray-800 transition-all shadow-2xl shadow-black/20 flex justify-center items-center gap-3">
                                <span wire:loading.remove>Kirim Inquiry</span>
                                <span wire:loading
                                    class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>