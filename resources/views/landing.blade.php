<!DOCTYPE html>
<html lang="id">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>KOC Apparel — Wear Your Identity</title>
 <meta name="description" content="Brand apparel dengan desain original dan printing berkualitas tinggi. Custom baju untuk komunitas, event, dan brand.">
 <link rel="preconnect" href="https://fonts.googleapis.com">
 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
 <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Syne:wght@400;700;800&display=swap" rel="stylesheet">
 <script src="https://unpkg.com/lucide@latest"></script>
 @vite(['resources/css/app.css'])
 <style>
  :root { --brand-black: #0a0a0a; --brand-gray: #6b7280; --brand-light: #f5f5f5; }
  body { font-family: 'Inter', sans-serif; }
  .font-heading { font-family: 'Syne', sans-serif; }
  .reveal { opacity: 0; transform: translateY(30px); transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1); }
  .reveal.visible { opacity: 1; transform: translateY(0); }
  .pulse-btn { animation: pulse-glow 2s infinite; }
  @keyframes pulse-glow { 0%, 100% { box-shadow: 0 0 0 0 rgba(10,10,10,0.15); } 50% { box-shadow: 0 0 0 10px rgba(10,10,10,0); } }
  .marquee-track { animation: marquee 30s linear infinite; }
  @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
  .nav-scrolled { background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); border-bottom: 1px solid #e5e7eb; }
 </style>
 @livewireStyles
</head>
<body class="h-full">
 <div id="app-wrapper" class="w-full h-full overflow-auto bg-white text-gray-900 relative z-0">
  
  {{-- Navbar --}}
  @livewire('navbar')
  <div class="h-16 sm:h-20"></div>

  {{-- HERO SECTION --}}
  <section id="hero" class="relative min-h-[85vh] flex items-center bg-gray-50">
   <div class="max-w-7xl mx-auto px-6 py-20 w-full">
    <div class="grid lg:grid-cols-2 gap-12 items-center">
     {{-- Left: Value Prop --}}
     <div class="order-2 lg:order-1">
      <div class="reveal inline-flex items-center gap-2 bg-white border border-gray-200 rounded-full px-4 py-1.5 text-xs font-medium text-gray-500 mb-6 shadow-sm">
       <span class="w-2 h-2 bg-green-500 rounded-full"></span>
       Open Order — Fast Production 7-14 Days
      </div>
      
      <h1 class="reveal font-heading font-800 text-5xl md:text-7xl leading-[1.05] tracking-tight mb-6">
       Wear Your<br><span class="italic text-gray-600">Identity.</span>
      </h1>
      
      <p class="reveal text-gray-600 text-lg md:text-xl max-w-md leading-relaxed mb-8">
       Desain original, printing premium. Bukan mass-produced — tiap piece punya cerita.
      </p>

      {{-- Two Clear Paths --}}
      <div class="reveal space-y-3">
       {{-- Path 1: Retail (Shopee) --}}
       <a href="https://shopee.co.id/" target="_blank" rel="noopener"
          class="group flex items-center gap-4 p-4 bg-white rounded-2xl border-2 border-orange-100 hover:border-orange-500 transition-all shadow-sm hover:shadow-md">
        <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center text-white">
         <i data-lucide="shopping-bag" class="w-6 h-6"></i>
        </div>
        <div class="flex-1">
         <div class="font-semibold text-gray-900">Beli Satuan</div>
         <div class="text-sm text-gray-500">Ready stock via Shopee — Gratis Ongkir</div>
        </div>
        <i data-lucide="external-link" class="w-5 h-5 text-gray-400 group-hover:text-orange-500"></i>
       </a>

       {{-- Path 2: Wholesale (Order Builder) --}}
       <a href="{{ route('order.builder') }}"
          class="group flex items-center gap-4 p-4 bg-gray-900 rounded-2xl border-2 border-gray-900 hover:bg-gray-800 transition-all shadow-md hover:shadow-lg">
        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-gray-900">
         <i data-lucide="shirt" class="w-6 h-6"></i>
        </div>
        <div class="flex-1">
         <div class="font-semibold text-white">Pesan Grosir / Custom</div>
         <div class="text-sm text-gray-400">Min. 12 pcs — Harga lebih hemat</div>
        </div>
        <i data-lucide="arrow-right" class="w-5 h-5 text-gray-500 group-hover:text-white"></i>
       </a>
      </div>

      {{-- Track Order Link --}}
      <div class="reveal mt-4 text-center lg:text-left">
       <a href="{{ route('order.tracking') }}" class="text-sm text-gray-500 hover:text-gray-900 underline">
        Sudah order? Lacak pesanan Anda →
       </a>
      </div>
     </div>

     {{-- Right: Hero Image --}}
     <div class="reveal order-1 lg:order-2 relative">
      <div class="relative aspect-square max-w-lg mx-auto lg:ml-auto">
       <img src="{{ asset('landing-page/hero.jpeg') }}" alt="KOC Apparel" 
            class="w-full h-full object-cover rounded-3xl shadow-2xl">
       <div class="absolute -bottom-4 -left-4 bg-white rounded-2xl px-5 py-3 shadow-lg border border-gray-100">
        <div class="flex items-center gap-3">
         <div class="w-10 h-10 bg-gray-900 rounded-full flex items-center justify-center">
          <i data-lucide="star" class="w-5 h-5 text-white"></i>
         </div>
         <div>
          <div class="font-heading font-700 text-sm">Original Design</div>
          <div class="text-xs text-gray-500">100% Karya Sendiri</div>
         </div>
        </div>
       </div>
      </div>
     </div>
    </div>
   </div>
  </section>

  {{-- Marquee --}}
  <section class="border-y border-gray-200 py-3 bg-white overflow-hidden">
   <div class="marquee-track whitespace-nowrap">
    <span class="inline-flex items-center gap-8 px-4 text-sm font-mono text-gray-500">
     @foreach(['★ ORIGINAL DESIGN', '★ PREMIUM PRINTING', '★ CUSTOM AVAILABLE', '★ PRE-ORDER & SATUAN', '★ MADE IN INDONESIA'] as $item)
      <span>{{ $item }}</span>
     @endforeach
     @foreach(['★ ORIGINAL DESIGN', '★ PREMIUM PRINTING', '★ CUSTOM AVAILABLE', '★ PRE-ORDER & SATUAN', '★ MADE IN INDONESIA'] as $item)
      <span>{{ $item }}</span>
     @endforeach
    </span>
   </div>
  </section>

  {{-- BEST COLLECTION --}}
  <section id="katalog" class="py-20 bg-white">
   <div class="max-w-7xl mx-auto px-6">
    <div class="reveal flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10">
     <div>
      <span class="text-xs font-mono text-gray-500 uppercase tracking-widest">Featured</span>
      <h2 class="font-heading font-800 text-3xl md:text-4xl mt-1">Koleksi Terbaik</h2>
     </div>
     <a href="{{ route('catalog.index') }}" class="text-sm font-medium text-gray-900 hover:text-gray-600 flex items-center gap-1">
      Lihat Semua <i data-lucide="arrow-right" class="w-4 h-4"></i>
     </a>
    </div>

    {{-- Product Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
     @php
      $featuredProducts = \App\Models\Product::with(['category', 'gallery' => function($q) {
       $q->orderByDesc('is_primary')->orderBy('sort_order');
      }])->featured()->take(4)->get();
     @endphp
     
     @forelse($featuredProducts as $index => $product)
      @php
       $galleryImage = optional($product->gallery->first())->image_url;
       $img = $galleryImage ? (\Illuminate\Support\Str::startsWith($galleryImage, 'http')
        ? $galleryImage
        : asset('storage/' . preg_replace('/^storage\//', '', $galleryImage)))
        : null;
       $firstInventory = \App\Models\Inventory::where('product_id', $product->id)->first();
       $price = $firstInventory ? $firstInventory->final_price : 0;
      @endphp
      
      <div class="reveal group rounded-2xl overflow-hidden cursor-pointer bg-gray-50" style="animation-delay: {{ $index * 0.1 }}s;">
       <div class="aspect-[3/4] relative">
        @if($img)
         <img src="{{ $img }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        @else
         <div class="absolute inset-0 flex items-center justify-center text-6xl bg-gray-200 text-gray-400">
          {{ substr($product->name, 0, 1) }}
         </div>
        @endif
        
        {{-- Hover Actions --}}
        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
         <a href="{{ route('catalog.index') }}" class="bg-white text-gray-900 text-sm font-medium px-4 py-2 rounded-full hover:bg-gray-100">
          Lihat Detail
         </a>
        </div>
       </div>
       <div class="p-4 bg-white">
        <span class="text-xs text-gray-500">{{ $product->category->name ?? 'Product' }}</span>
        <h3 class="font-semibold mt-1 truncate">{{ $product->name }}</h3>
        <p class="text-sm font-mono text-gray-900 mt-1">
         {{ $price > 0 ? 'Rp ' . number_format($price, 0, ',', '.') : 'Tanya Harga' }}
        </p>
       </div>
      </div>
     @empty
      <div class="col-span-full text-center py-12 text-gray-500">
       Belum ada produk unggulan.
      </div>
     @endforelse
    </div>
   </div>
  </section>

  {{-- HOW IT WORKS --}}
  <section id="cara-order" class="py-20 bg-gray-50">
   <div class="max-w-4xl mx-auto px-6">
    <div class="reveal text-center mb-12">
     <span class="text-xs font-mono text-gray-500 uppercase tracking-widest">How To Order</span>
     <h2 class="font-heading font-800 text-3xl md:text-4xl mt-2">Cara Pesan</h2>
     <p class="text-gray-600 mt-3">2 pilihan, sesuai kebutuhan Anda</p>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
     {{-- Option 1: Shopee --}}
     <div class="reveal bg-white rounded-2xl p-6 border border-gray-200">
      <div class="flex items-center gap-3 mb-4">
       <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
        <i data-lucide="shopping-cart" class="w-5 h-5 text-orange-600"></i>
       </div>
       <div>
        <h3 class="font-semibold">Beli Satuan</h3>
        <span class="text-xs bg-orange-100 text-orange-700 px-2 py-0.5 rounded-full">Ready Stock</span>
       </div>
      </div>
      <ol class="space-y-3 text-sm text-gray-600 mb-6">
       <li class="flex gap-3">
        <span class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center text-xs font-medium flex-shrink-0">1</span>
        <span>Buka toko kami di Shopee</span>
       </li>
       <li class="flex gap-3">
        <span class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center text-xs font-medium flex-shrink-0">2</span>
        <span>Pilih produk & checkout</span>
       </li>
       <li class="flex gap-3">
        <span class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center text-xs font-medium flex-shrink-0">3</span>
        <span>Barang dikirim H+1</span>
       </li>
      </ol>
      <a href="https://shopee.co.id/" target="_blank" rel="noopener"
         class="block w-full py-3 bg-orange-500 text-white text-center rounded-xl font-medium hover:bg-orange-600 transition-colors">
       Ke Shopee →
      </a>
     </div>

     {{-- Option 2: Custom/PO --}}
     <div class="reveal bg-gray-900 rounded-2xl p-6" style="animation-delay: 0.1s;">
      <div class="flex items-center gap-3 mb-4">
       <div class="w-10 h-10 bg-gray-700 rounded-lg flex items-center justify-center">
        <i data-lucide="shirt" class="w-5 h-5 text-white"></i>
       </div>
       <div>
        <h3 class="font-semibold text-white">Pesan Grosir</h3>
        <span class="text-xs bg-gray-700 text-gray-300 px-2 py-0.5 rounded-full">Min. 12 pcs</span>
       </div>
      </div>
      <ol class="space-y-3 text-sm text-gray-400 mb-6">
       <li class="flex gap-3">
        <span class="w-6 h-6 bg-gray-800 rounded-full flex items-center justify-center text-xs font-medium flex-shrink-0">1</span>
        <span>Isi form Order Builder</span>
       </li>
       <li class="flex gap-3">
        <span class="w-6 h-6 bg-gray-800 rounded-full flex items-center justify-center text-xs font-medium flex-shrink-0">2</span>
        <span>Deal & DP 50% via WA</span>
       </li>
       <li class="flex gap-3">
        <span class="w-6 h-6 bg-gray-800 rounded-full flex items-center justify-center text-xs font-medium flex-shrink-0">3</span>
        <span>Produksi 7-14 hari</span>
       </li>
      </ol>
      <a href="{{ route('order.builder') }}"
         class="block w-full py-3 bg-white text-gray-900 text-center rounded-xl font-medium hover:bg-gray-100 transition-colors">
       Mulai Custom →
      </a>
     </div>
    </div>
   </div>
  </section>

  {{-- WHY CHOOSE US --}}
  <section id="keunggulan" class="py-20 bg-white">
   <div class="max-w-5xl mx-auto px-6">
    <div class="reveal text-center mb-12">
     <span class="text-xs font-mono text-gray-500 uppercase tracking-widest">Why Us</span>
     <h2 class="font-heading font-800 text-3xl md:text-4xl mt-2">Kenapa Pilih Kami?</h2>
    </div>
    
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
     @php
      $features = [
       ['icon' => 'pen-tool', 'title' => 'Desain Original', 'desc' => '100% desain sendiri'],
       ['icon' => 'printer', 'title' => 'Premium Print', 'desc' => 'DTG & Sublim anti luntur'],
       ['icon' => 'shield', 'title' => 'QC Ketat', 'desc' => 'Cek kualitas sebelum kirim'],
       ['icon' => 'zap', 'title' => 'Proses Cepat', 'desc' => 'PO 7-14 hari kerja'],
      ];
     @endphp
     
     @foreach($features as $i => $f)
      <div class="reveal bg-gray-50 rounded-2xl p-6" style="animation-delay: {{ $i * 0.1 }}s;">
       <div class="w-12 h-12 bg-gray-900 rounded-xl flex items-center justify-center mb-4">
        <i data-lucide="{{ $f['icon'] }}" class="w-5 h-5 text-white"></i>
       </div>
       <h3 class="font-semibold mb-1">{{ $f['title'] }}</h3>
       <p class="text-sm text-gray-500">{{ $f['desc'] }}</p>
      </div>
     @endforeach
    </div>
   </div>
  </section>

  {{-- FINAL CTA --}}
  <section class="py-20 bg-gray-900 text-white">
   <div class="max-w-3xl mx-auto px-6 text-center">
    <div class="reveal">
     <h2 class="font-heading font-800 text-4xl md:text-5xl mb-4">
      Siap Tampil <span class="italic text-gray-400">Beda?</span>
     </h2>
     <p class="text-gray-400 mb-8 max-w-lg mx-auto">
      Jadilah bagian dari yang paham bedanya desain original vs mass-produced.
     </p>
     <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
      <a href="{{ route('order.builder') }}" class="pulse-btn inline-flex items-center gap-2 bg-white text-gray-900 font-medium px-8 py-4 rounded-full hover:bg-gray-100 transition-colors">
       <i data-lucide="shirt" class="w-5 h-5"></i>
       Pesan Custom Sekarang
      </a>
      <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 border border-gray-600 text-white font-medium px-8 py-4 rounded-full hover:bg-gray-800 transition-colors">
       <i data-lucide="grid" class="w-5 h-5"></i>
       Lihat Katalog
      </a>
     </div>
    </div>
   </div>
  </section>

  {{-- FOOTER --}}
  <footer class="bg-gray-950 text-gray-400 py-12">
   <div class="max-w-7xl mx-auto px-6">
    <div class="grid md:grid-cols-4 gap-8 mb-8">
     <div class="md:col-span-2">
      <span class="font-heading font-800 text-2xl text-white">KOC</span>
      <p class="mt-2 text-sm max-w-xs">Apparel brand dengan desain original. Wear your identity.</p>
     </div>
     <div>
      <h4 class="text-white font-medium text-sm mb-3">Navigasi</h4>
      <div class="space-y-2 text-sm">
       <a href="{{ route('catalog.index') }}" class="block hover:text-white transition-colors">Katalog</a>
       <a href="{{ route('order.builder') }}" class="block hover:text-white transition-colors">Order Custom</a>
       <a href="{{ route('order.tracking') }}" class="block hover:text-white transition-colors">Lacak Pesanan</a>
      </div>
     </div>
     <div>
      <h4 class="text-white font-medium text-sm mb-3">Kontak</h4>
      <div class="space-y-2 text-sm">
       <a href="https://wa.me/6289513229597" target="_blank" rel="noopener" class="flex items-center gap-2 hover:text-white transition-colors">
        <i data-lucide="message-circle" class="w-4 h-4"></i> WhatsApp
       </a>
       <a href="https://shopee.co.id/" target="_blank" rel="noopener" class="flex items-center gap-2 hover:text-white transition-colors">
        <i data-lucide="shopping-bag" class="w-4 h-4"></i> Shopee
       </a>
      </div>
     </div>
    </div>
    <div class="border-t border-gray-900 pt-6 flex flex-col md:flex-row items-center justify-between gap-4 text-xs">
     <p>© {{ date('Y') }} KOC Apparel. All rights reserved.</p>
     <p class="text-gray-600">Made with 🖤 in Indonesia</p>
    </div>
   </div>
  </footer>

 </div>

 <script>
  function initObserver() {
   const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => {
     if (e.isIntersecting) {
      e.target.classList.add('visible');
      observer.unobserve(e.target);
     }
    });
   }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
   
   document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
  }

  function initNav() {
   const nav = document.getElementById('navbar');
   const wrapper = document.getElementById('app-wrapper');
   if (nav && wrapper) {
    wrapper.addEventListener('scroll', () => {
     nav.classList.toggle('nav-scrolled', wrapper.scrollTop > 60);
    });
   }
  }

  function initMobile() {
   const menu = document.getElementById('mobile-menu');
   const toggle = document.getElementById('mobile-toggle');
   const close = document.getElementById('mobile-close');
   
   if (toggle && menu) {
    toggle.addEventListener('click', () => menu.classList.add('open'));
   }
   if (close && menu) {
    close.addEventListener('click', () => menu.classList.remove('open'));
   }
   if (menu) {
    menu.querySelectorAll('.mobile-link').forEach(link => {
     link.addEventListener('click', () => menu.classList.remove('open'));
    });
   }
  }

  document.addEventListener('DOMContentLoaded', () => {
   lucide.createIcons();
   initObserver();
   initNav();
   initMobile();
  });
 </script>
 @livewireScripts
</body>
</html>
