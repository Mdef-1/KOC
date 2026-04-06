<div class="bg-white min-h-screen selection:bg-brand-black selection:text-white text-brand-black">
    {{-- Hero Section --}}
    <section class="relative pt-32 pb-16 px-4 sm:px-6">
        <div class="max-w-7xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 border border-neutral-200 rounded-full px-4 py-1.5 text-xs font-mono text-brand-gray mb-6">
                <span class="w-2 h-2 bg-green-500 rounded-full inline-block"></span>
                Our Best Collection
            </div>

            <h1 class="font-heading font-800 text-5xl sm:text-7xl lg:text-8xl leading-[0.9] tracking-tight uppercase mb-8">
                Katalog <br /><span class="italic">Produk</span>
            </h1>

            @if (session()->has('success'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
                    x-transition:leave="transition ease-in duration-500"
                    class="mb-8 max-w-md mx-auto rounded-2xl bg-brand-black text-white px-8 py-4 text-xs font-bold uppercase tracking-widest shadow-2xl">
                    {{ session('success') }}
                </div>
            @endif

            <p class="text-brand-gray text-base sm:text-lg max-w-xl mx-auto leading-relaxed">
                @if($search || $category)
                    Menampilkan hasil untuk <span class="text-brand-black font-bold">"{{ $search ?: $category }}"</span>
                @else
                    Setiap desain dibuat dengan konsep yang matang. <br class="hidden sm:block">Printing berkualitas, bahan nyaman.
                @endif
            </p>
        </div>
    </section>

    {{-- Search & Filter --}}
    <section class="pb-12 px-4">
        <div class="max-w-4xl mx-auto space-y-6">
            {{-- Search Input --}}
            <div class="relative group">
                <input type="text" placeholder="Cari koleksi spesifik..." wire:model.live="search"
                    class="w-full px-8 py-5 rounded-2xl border border-neutral-200 bg-white shadow-sm focus:ring-2 focus:ring-neutral-200 transition-all duration-500 text-sm font-medium placeholder:text-neutral-400" />
                <div class="absolute right-6 top-1/2 -translate-y-1/2 text-neutral-400 group-focus-within:text-brand-black transition-colors">
                    <i data-lucide="search" class="w-5 h-5"></i>
                </div>
            </div>

            {{-- Category Filter --}}
            <div class="flex flex-wrap justify-center gap-2">
                <button wire:click="setCategory('')"
                    class="px-6 py-2.5 text-xs font-bold uppercase tracking-wider rounded-full transition-all border {{ $category === '' ? 'bg-brand-black text-white border-brand-black' : 'bg-white text-brand-gray border-neutral-200 hover:border-brand-black hover:text-brand-black' }}">
                    Semua
                </button>
                @foreach($categories as $cat)
                    <button wire:key="cat-{{ $cat->id }}" wire:click="setCategory('{{ $cat->slug }}')"
                        class="px-6 py-2.5 text-xs font-bold uppercase tracking-wider rounded-full transition-all border {{ $category === $cat->slug ? 'bg-brand-black text-white border-brand-black' : 'bg-white text-brand-gray border-neutral-200 hover:border-brand-black hover:text-brand-black' }}">
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Product Grid --}}
    <section class="pb-24 px-4 sm:px-6">
        <div class="max-w-7xl mx-auto">
            {{-- Loading State --}}
            <div wire:loading.flex class="justify-center items-center py-20">
                <div class="w-10 h-10 rounded-full border-[3px] border-neutral-100 border-t-brand-black animate-spin"></div>
            </div>

            {{-- Product Grid --}}
            <div wire:loading.remove>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
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
                            {{-- Image Card --}}
                            <a href="{{ route('product.detail', ['id' => $product->id]) }}" wire:navigate
                               class="relative aspect-[3/4] bg-neutral-100 rounded-2xl mb-4 overflow-hidden hover-lift block">
                                @if($img)
                                    <img src="{{ $img }}" alt="{{ $product->name }}"
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-neutral-50 text-neutral-200">
                                        <span class="font-heading font-800 text-5xl uppercase">{{ substr($product->name, 0, 1) }}</span>
                                    </div>
                                @endif

                                {{-- Hover Overlay - Desktop only --}}
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 items-center justify-center p-4 hidden lg:flex">
                                    <div class="space-y-2 w-full max-w-[160px]">
                                        <span class="block w-full py-2.5 bg-white text-brand-black text-center rounded-xl text-xs font-bold uppercase tracking-wider">
                                            Lihat Detail
                                        </span>
                                    </div>
                                </div>
                            </a>

                            {{-- Product Info --}}
                            <div class="px-1">
                                <span class="text-[10px] font-mono text-brand-gray uppercase tracking-wider">{{ $product->category->name ?? 'Essentials' }}</span>
                                <h4 class="font-heading font-700 text-base sm:text-lg mt-1 leading-tight group-hover:text-brand-gray transition-colors">
                                    {{ $product->name }}
                                </h4>
                                <p class="font-mono text-sm mt-1 text-brand-black">
                                    {{ $product->price ? 'Rp ' . number_format($product->price, 0, ',', '.') : 'Tanya Harga' }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-24">
                            <p class="text-brand-gray font-medium">Tidak ada produk yang ditemukan.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                <div class="mt-16">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </section>

    {{-- Inquiry Modal --}}
    <div x-data="{ open: @entangle('showInquiryModal') }" x-show="open"
        x-effect="document.body.style.overflow = open ? 'hidden' : 'auto'"
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" style="display: none;">

        <div class="absolute inset-0 bg-brand-black/90 backdrop-blur-sm transition-opacity" x-show="open"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" wire:click="closeInquiry"></div>

        @if($selectedProduct)
            <div class="relative w-full max-w-4xl bg-white rounded-3xl shadow-2xl overflow-hidden"
                x-show="open" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0">

                <div class="grid md:grid-cols-2">
                    {{-- Image Side --}}
                    <div class="hidden md:block relative bg-neutral-100">
                        @php
                            $modalImg = optional($selectedProduct->gallery->first())->image_url ?? $selectedProduct->image_url;
                            $modalImgUrl = $modalImg ? (\Illuminate\Support\Str::startsWith($modalImg, 'http')
                                ? $modalImg
                                : asset('storage/' . preg_replace('/^storage\//', '', $modalImg))) : null;
                        @endphp
                        <img src="{{ $modalImgUrl }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-8 left-8 text-white">
                            <h3 class="font-heading font-700 text-3xl italic tracking-tighter">{{ $selectedProduct->name }}</h3>
                            <p class="text-[10px] font-mono uppercase tracking-widest opacity-70 mt-1">{{ $selectedProduct->category->name ?? 'Produk' }}</p>
                        </div>
                    </div>

                    {{-- Form Side --}}
                    <div class="p-8 sm:p-12">
                        <div class="flex justify-between items-start mb-8">
                            <div>
                                <h2 class="font-heading font-800 text-2xl uppercase italic tracking-tighter">Inquiry</h2>
                                <div class="h-1 w-10 bg-brand-black mt-3"></div>
                            </div>
                            <button wire:click="closeInquiry" class="text-neutral-300 hover:text-brand-black transition-colors">
                                <i data-lucide="x" class="w-6 h-6"></i>
                            </button>
                        </div>

                        <form wire:submit.prevent="submitInquiry" class="space-y-6">
                            <div class="relative group">
                                <input type="text" wire:model="customer_name" required
                                    class="w-full border-b-2 border-neutral-100 focus:border-brand-black transition-all outline-none py-3 text-sm font-medium peer bg-transparent">
                                <label class="absolute left-0 top-3 text-xs font-medium text-neutral-400 pointer-events-none transition-all peer-focus:-top-2 peer-focus:text-brand-black peer-valid:-top-2 peer-valid:text-brand-black">Nama Lengkap</label>
                            </div>

                            <div class="relative group">
                                <input type="text" wire:model="customer_contact" required
                                    class="w-full border-b-2 border-neutral-100 focus:border-brand-black transition-all outline-none py-3 text-sm font-medium peer bg-transparent">
                                <label class="absolute left-0 top-3 text-xs font-medium text-neutral-400 pointer-events-none transition-all peer-focus:-top-2 peer-focus:text-brand-black peer-valid:-top-2 peer-valid:text-brand-black">Kontak (WA/Email)</label>
                            </div>

                            <div class="relative group">
                                <textarea wire:model="message" rows="3" required
                                    class="w-full border-b-2 border-neutral-100 focus:border-brand-black transition-all outline-none py-3 text-sm font-medium peer bg-transparent resize-none"></textarea>
                                <label class="absolute left-0 top-3 text-xs font-medium text-neutral-400 pointer-events-none transition-all peer-focus:-top-2 peer-focus:text-brand-black peer-valid:-top-2 peer-valid:text-brand-black">Pesan Singkat</label>
                            </div>

                            <button type="submit" wire:loading.attr="disabled"
                                class="w-full py-4 bg-brand-black text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-neutral-800 transition-colors flex justify-center items-center gap-3 mt-8">
                                <span wire:loading.remove>Kirim Inquiry</span>
                                <span wire:loading class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
