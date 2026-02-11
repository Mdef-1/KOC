<div class="py-20 px-6">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-4xl md:text-5xl font-bold text-center mb-8">Katalog Produk</h1>

        @if (session()->has('success'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                class="mb-6 max-w-2xl mx-auto rounded-xl border border-green-200 bg-green-50 text-green-800 px-4 py-3 text-sm text-center">
                {{ session('success') }}
            </div>
        @endif

        @if($search || $category)
            <p class="text-center text-lg opacity-70 mb-8">
                @if($search)
                    Hasil untuk "{{ $search }}"
                @endif
                @if($search && $category)
                    •
                @endif
                @if($category)
                    Kategori: {{ optional($categories->firstWhere('slug', $category))->name ?? 'Semua' }}
                @endif
            </p>
        @else
            <p class="text-center text-lg opacity-70 mb-12 max-w-2xl mx-auto">Jelajahi koleksi lengkap pakaian olahraga
                unisex kami.</p>
        @endif

        <div class="max-w-3xl mx-auto mb-8">
            <input type="text" placeholder="Cari produk..." wire:model.live="search"
                class="w-full px-5 py-3 rounded-full border border-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-950" />
        </div>

        <div class="flex flex-wrap justify-center gap-3 mb-12">
            <button wire:click="setCategory('')"
                class="px-6 py-2 rounded-full font-medium transition-all {{ $category === '' ? 'bg-gray-950 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-800' }}">Semua
                Produk</button>
            @foreach($categories as $cat)
                <button wire:key="cat-{{ $cat->id }}" wire:click="setCategory('{{ $cat->slug }}')"
                    class="px-6 py-2 rounded-full font-medium transition-all {{ $category === $cat->slug ? 'bg-gray-950 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-800' }}">{{ $cat->name }}</button>
            @endforeach
        </div>

        <div wire:loading.flex class="justify-center items-center py-20">
            <div class="w-6 h-6 rounded-full border-2 border-gray-300 border-t-gray-900 animate-spin"></div>
        </div>

        <div wire:loading.remove>
            <div class="grid md:grid-cols-4 gap-6">

                @forelse($products as $product)
                    @php
                        $galleryImage = optional($product->gallery->first())->image_url;
                        $img = $galleryImage ? \Illuminate\Support\Str::startsWith($galleryImage, 'http')
                            ? $galleryImage
                            : (\Illuminate\Support\Str::startsWith($galleryImage, 'storage/')
                                ? asset($galleryImage)
                                : (\Illuminate\Support\Str::startsWith($galleryImage, 'product-gallery/')
                                    ? asset('storage/' . $galleryImage)
                                    : asset('storage/' . $galleryImage)))
                            : ($product->image_url
                                ? (\Illuminate\Support\Str::startsWith($product->image_url, 'http')
                                    ? $product->image_url
                                    : (\Illuminate\Support\Str::startsWith($product->image_url, 'products/')
                                        ? asset('storage/' . $product->image_url)
                                        : asset('storage/products/' . $product->image_url)))
                                : null);
                    @endphp
                    <a href="{{ route('product.detail', ['id' => $product->id]) }}" wire:navigate>
                        <div class="group cursor-pointer">
                            <div class="aspect-[3/4] rounded-2xl mb-4 overflow-hidden bg-gray-100">
                                @if($img)
                                    <img wire:click src="{{ $img }}" alt="{{ $product->image_alt ?? $product->name }}"
                                        class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                        onerror="this.onerror=null; this.src='data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22300%22%20height%3D%22400%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20300%20400%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_18c2c3a3f9d%20text%20%7B%20fill%3A%23AAAAAA%3Bfont-weight%3Abold%3Bfont-family%3AArial%2C%20Helvetica%2C%20Open%20Sans%2C%20sans-serif%2C%20monospace%3Bfont-size%3A15pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_18c2c3a3f9d%22%3E%3Crect%20width%3D%22300%22%20height%3D%22400%22%20fill%3D%22%23F5F5F5%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%22110.5%22%20y%3D%22220%22%3ENo%20Image%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E';">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg viewBox="0 0 300 400" class="w-full h-full">
                                            <rect width="300" height="400" fill="currentColor" opacity="0.08" />
                                            <circle cx="150" cy="120" r="30" fill="currentColor" opacity="0.15" />
                                            <rect x="110" y="160" width="80" height="180" rx="10" fill="currentColor"
                                                opacity="0.15" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <h3 class="text-lg font-semibold mb-1">{{ $product->name }}</h3>
                            @if(!is_null($product->price))
                                <p class="opacity-60">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</p>
                            @else
                                <p class="opacity-60">Hubungi kami</p>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center py-16">
                        <p class="text-lg opacity-70">Tidak ada produk ditemukan.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-10">
                {{ $products->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
    @if($showInquiryModal)
        <div class="fixed inset-0 z-50" wire:keydown.escape.window="closeInquiry">
            <div class="absolute inset-0 bg-black/50" wire:click="closeInquiry"></div>
            <div class="relative mx-auto max-w-lg px-4 top-24">
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-6 md:p-8">
                        <div class="flex items-start gap-4 mb-6">
                            <div class="w-24 h-32 rounded-xl overflow-hidden bg-gray-100 shrink-0">
                                @php
                                    $selImg = null;
                                    if (isset($selectedProduct) && $selectedProduct->gallery->isNotEmpty()) {
                                        $g = $selectedProduct->gallery->first()->image_url;

                                        if (\Illuminate\Support\Str::startsWith($g, ['http://', 'https://'])) {
                                            $selImg = $g;
                                        } else {
                                            // Bersihkan semua kemungkinan prefix 'storage/' atau '/' agar tidak double
                                            $path = ltrim($g, '/');
                                            $path = preg_replace('/^storage\//', '', $path);
                                            $selImg = asset('storage/' . $path);
                                        }
                                    }
                                @endphp
                                @if($selImg)
                                    <a href="">
                                        <img src="{{ $selImg }}" alt="{{ $selectedProduct->name ?? 'Produk' }}"
                                            class="w-full h-full object-cover">
                                    </a>
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg viewBox="0 0 300 400" class="w-full h-full">
                                            <rect width="300" height="400" fill="currentColor" opacity="0.08" />
                                            <circle cx="150" cy="120" r="30" fill="currentColor" opacity="0.15" />
                                            <rect x="110" y="160" width="80" height="180" rx="10" fill="currentColor"
                                                opacity="0.15" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-semibold text-lg leading-tight">{{ $selectedProduct->name ?? 'Produk' }}
                                </h3>
                                <p class="text-sm text-gray-500">Silakan isi formulir di bawah untuk mengirimkan inquiry.
                                </p>
                            </div>
                            <button class="ml-auto text-gray-400 hover:text-gray-600" wire:click="closeInquiry"
                                aria-label="Tutup">
                                ✕
                            </button>
                        </div>

                        <form wire:submit.prevent="submitInquiry" class="grid gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Nama</label>
                                <input type="text" wire:model.defer="customer_name"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-900">
                                @error('customer_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Kontak (Email/WA)</label>
                                <input type="text" wire:model.defer="customer_contact"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-900">
                                @error('customer_contact')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Pesan</label>
                                <textarea rows="4" wire:model.defer="message"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-900"
                                    placeholder="Saya tertarik dengan produk ini..."></textarea>
                                @error('message')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            @error('selectedProductId')<p class="text-sm text-red-600">{{ $message }}</p>@enderror

                            <div class="flex items-center justify-end gap-3 pt-2">
                                <button type="button" class="px-4 py-2 rounded-xl border border-gray-200"
                                    wire:click="closeInquiry">Batal</button>
                                <button type="submit" class="px-5 py-2 rounded-xl bg-gray-950 text-white">Kirim
                                    Inquiry</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>