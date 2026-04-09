<div class="bg-white selection:bg-brand-black selection:text-white text-brand-black">
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
            {{-- Skeleton Loading State --}}
            <div wire:loading class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach(range(1, 8) as $i)
                    <div class="animate-pulse">
                        {{-- Skeleton Image --}}
                        <div class="aspect-[3/4] bg-neutral-200 rounded-2xl mb-4"></div>
                        {{-- Skeleton Text --}}
                        <div class="px-1 space-y-2">
                            <div class="h-3 bg-neutral-200 rounded w-1/3"></div>
                            <div class="h-4 bg-neutral-200 rounded w-3/4"></div>
                            <div class="h-4 bg-neutral-200 rounded w-1/2"></div>
                        </div>
                    </div>
                @endforeach
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
                            $isOutOfStock = $product->total_stock <= 0;
                        @endphp

                        <div class="group {{ $isOutOfStock ? 'opacity-75' : '' }}" wire:key="prod-{{ $product->id }}">
                            {{-- Image Card --}}
                            <div class="relative aspect-[3/4] bg-neutral-100 rounded-2xl mb-4 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                                {{-- Out of Stock Badge --}}
                                @if($isOutOfStock)
                                    <div class="absolute top-3 left-3 z-10 bg-red-500 text-white px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                        Stok Habis
                                    </div>
                                @endif

                                <a href="{{ route('product.detail', ['id' => $product->id]) }}" wire:navigate class="block w-full h-full">
                                    @if($img)
                                        <img src="{{ $img }}" alt="{{ $product->name }}"
                                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 {{ $isOutOfStock ? 'grayscale blur-[2px]' : '' }}">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-neutral-50 text-neutral-200 {{ $isOutOfStock ? 'grayscale' : '' }}">
                                            <span class="font-heading font-800 text-5xl uppercase">{{ substr($product->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </a>

                                {{-- Hover Overlay - Desktop --}}
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 items-center justify-center p-4 hidden lg:flex gap-2">
                                    <a href="{{ route('product.detail', ['id' => $product->id]) }}" wire:navigate
                                       class="py-2.5 bg-white text-brand-black text-center rounded-xl text-xs font-bold uppercase tracking-wider px-4 hover:bg-gray-100 transition-colors">
                                        Lihat Detail
                                    </a>
                                    @if(!$isOutOfStock)
                                        <button wire:click="openInquiry({{ $product->id }})"
                                                class="py-2.5 bg-brand-black text-white text-center rounded-xl text-xs font-bold uppercase tracking-wider px-4 hover:bg-neutral-800 transition-colors">
                                            Tanya Harga
                                        </button>
                                    @endif
                                </div>

                                {{-- Mobile Action Button --}}
                                <div class="absolute bottom-3 left-3 right-3 flex gap-2 lg:hidden">
                                    <a href="{{ route('product.detail', ['id' => $product->id]) }}" wire:navigate
                                       class="flex-1 py-2 bg-white/90 backdrop-blur text-brand-black text-center rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-white transition-colors">
                                        Detail
                                    </a>
                                    @if(!$isOutOfStock)
                                        <button wire:click="openInquiry({{ $product->id }})"
                                                class="flex-1 py-2 bg-brand-black/90 backdrop-blur text-white text-center rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-brand-black transition-colors">
                                            Tanya
                                        </button>
                                    @endif
                                </div>
                            </div>

                            {{-- Product Info --}}
                            <div class="px-1">
                                <span class="text-[10px] font-mono text-brand-gray uppercase tracking-wider">{{ $product->category->name ?? 'Essentials' }}</span>
                                <h4 class="font-heading font-700 text-base sm:text-lg mt-1 leading-tight {{ $isOutOfStock ? 'text-brand-gray' : 'group-hover:text-brand-gray' }} transition-colors">
                                    {{ $product->name }}
                                </h4>
                                <p class="font-mono text-sm mt-1 {{ $isOutOfStock ? 'text-brand-gray' : 'text-brand-black' }}">
                                    @if($isOutOfStock)
                                        <span class="text-red-500 font-medium">Stok Habis</span>
                                    @else
                                        {{ $product->price ? 'Rp ' . number_format($product->price, 0, ',', '.') : 'Tanya Harga' }}
                                    @endif
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

    {{-- Inquiry Modal - temporarily disabled for scroll test --}}
    {{--
    <div x-data="{ open: @entangle('showInquiryModal') }" x-show="open"
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" style="display: none;">
        ... modal content ...
    </div>
    --}}
</div>
