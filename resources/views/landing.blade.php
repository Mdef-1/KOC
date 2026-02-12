<x-layouts.guest>
  <style>
    .stroke-text {
      -webkit-text-stroke: 1px #1e293b;
      color: transparent;
    }

    /* Di layar besar, stroke lebih tebal */
    @media (min-width: 768px) {
      .stroke-text {
        -webkit-text-stroke: 1.5px #1e293b;
      }
    }

    .pattern-bg {
      background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23000000' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .smooth-scroll {
      scroll-behavior: smooth;
    }
  </style>

  <body class="h-full smooth-scroll overflow-x-hidden bg-[#f8f9fa] text-slate-900 font-sans antialiased">
    <div id="app" class="w-full">



      <section class="relative pt-32 sm:pt-48 pb-16 sm:pb-32 px-4 sm:px-6">
        <div class="max-w-7xl mx-auto">
          <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <div class="space-y-6 sm:space-y-8 text-center lg:text-left order-2 lg:order-1">
              <div
                class="inline-flex items-center gap-2 text-[9px] sm:text-[11px] font-bold uppercase tracking-[0.3em] text-gray-400">
                <div class="w-8 sm:w-12 h-[1px] bg-gray-300"></div> Engineered for Endurance
              </div>
              <h1 class="text-5xl sm:text-7xl lg:text-[90px] font-black leading-[0.9] tracking-tighter uppercase">
                Nyaman <br /><span class="stroke-text">Tanpa Batas.</span>
              </h1>
              <p class="text-base sm:text-xl text-gray-500 max-w-md mx-auto lg:mx-0 leading-relaxed font-light">
                Teknologi serat <span class="font-bold text-black">Ultra-Durable</span> yang tetap lembut di kulit.
              </p>
              <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-6 sm:gap-8 pt-4">
                <a href="{{ route('catalog.index') }}" wire:navigate>
                  <button
                    class="w-full sm:w-auto px-10 py-5 bg-black text-white rounded-2xl font-bold uppercase text-xs tracking-[0.2em] shadow-2xl shadow-black/20 hover:-translate-y-1 transition-all">
                    Eksplor Koleksi
                  </button>
                </a>
                <div class="flex flex-col items-center lg:items-start">
                  <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Material Grade</p>
                  <p class="text-sm font-black">Aero-Weave™ 2.0</p>
                </div>
              </div>
            </div>

            <div class="relative group order-1 lg:order-2 px-4 sm:px-0">
              <div class="absolute -inset-2 sm:-inset-4 pattern-bg -z-10 opacity-50 rounded-[2rem] sm:rounded-[3rem]">
              </div>
              <div
                class="aspect-square bg-white rounded-[2rem] sm:rounded-[3rem] overflow-hidden shadow-2xl border-[8px] sm:border-[12px] border-white transition-transform duration-700 group-hover:rotate-1">
                <img src="{{ asset('landing-page/hero.jpeg') }}" alt="K.O.C Hero" class="w-full h-full object-cover">
                <div
                  class="absolute top-4 right-4 sm:top-6 sm:right-6 bg-black/80 backdrop-blur-md text-white px-3 py-1.5 sm:px-4 sm:py-2 rounded-full text-[8px] sm:text-[10px] font-bold tracking-widest uppercase border border-white/20">
                  Tested: 500+ Washes
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section id="durability" class="py-16 sm:py-24 px-4 sm:px-6 bg-white">
        <div class="max-w-7xl mx-auto">
          <div class="grid md:grid-cols-3 gap-4 sm:gap-6">
            <div
              class="md:col-span-2 bg-[#f3f4f6] p-8 sm:p-12 rounded-[2rem] sm:rounded-[2.5rem] flex flex-col justify-between overflow-hidden relative group">
              <div class="relative z-10">
                <h3 class="text-3xl sm:text-4xl font-black italic uppercase tracking-tighter mb-4">Kualitas <br>Tanpa
                  Kompromi</h3>
                <p class="max-w-xs text-gray-500 text-xs sm:text-sm leading-relaxed">Jahitan 'Double-Lock' untuk
                  durabilitas maksimal.</p>
              </div>
              <div class="mt-8 sm:mt-12 flex flex-wrap gap-2 sm:gap-4 relative z-10">
                <span
                  class="px-3 py-1.5 bg-white rounded-lg text-[8px] sm:text-[10px] font-bold uppercase tracking-widest shadow-sm">Anti-Abrasion</span>
                <span
                  class="px-3 py-1.5 bg-white rounded-lg text-[8px] sm:text-[10px] font-bold uppercase tracking-widest shadow-sm">Sweat-Wicking</span>
              </div>
              <div
                class="absolute top-0 right-0 w-32 sm:w-64 h-32 sm:h-64 bg-black/5 rounded-full -translate-y-1/2 translate-x-1/2">
              </div>
            </div>

            <div
              class="bg-black p-8 sm:p-12 rounded-[2rem] sm:rounded-[2.5rem] text-white flex flex-col justify-between">
              <div
                class="w-10 h-10 sm:w-12 sm:h-12 bg-white/10 rounded-full flex items-center justify-center mb-6 sm:mb-8">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
              </div>
              <h3 class="text-xl sm:text-2xl font-bold italic uppercase tracking-tighter mb-4 leading-tight">Garansi
                <br>Durabilitas
              </h3>
              <p class="text-gray-400 text-[10px] sm:text-xs leading-relaxed">Garansi 1 tahun untuk setiap produk K.O.C.
              </p>
            </div>
          </div>
        </div>
      </section>

      <section id="custom" class="py-20 sm:py-32 px-4 sm:px-6 bg-[#111] text-white overflow-hidden relative">
        <div class="absolute inset-0 opacity-10 pattern-bg invert"></div>
        <div class="max-w-7xl mx-auto relative z-10">
          <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            <div class="order-2 lg:order-1">
              <div class="grid grid-cols-2 gap-3 sm:gap-4">
                <div
                  class="aspect-square bg-white/5 rounded-2xl sm:rounded-3xl border border-white/10 p-3 sm:p-4 hover:bg-white/10 transition-colors cursor-pointer">
                  <div class="w-full h-full pattern-bg opacity-30 rounded-lg sm:rounded-xl"></div>
                  <p class="text-[8px] sm:text-[10px] mt-2 sm:mt-4 font-bold tracking-widest text-center uppercase">
                    Cyber</p>
                </div>
                <div
                  class="aspect-square bg-white/5 rounded-2xl sm:rounded-3xl border border-white/10 p-3 sm:p-4 hover:bg-white/10 transition-colors cursor-pointer mt-6 sm:mt-8">
                  <div
                    class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-500 opacity-30 rounded-lg sm:rounded-xl">
                  </div>
                  <p class="text-[8px] sm:text-[10px] mt-2 sm:mt-4 font-bold tracking-widest text-center uppercase">Neon
                  </p>
                </div>
              </div>
            </div>

            <div class="order-1 lg:order-2 space-y-6 sm:space-y-8 text-center lg:text-left">
              <h2 class="text-4xl sm:text-6xl lg:text-7xl font-black italic uppercase tracking-tighter leading-none">
                Desain<br /><span class="text-gray-500 text-3xl sm:text-5xl lg:text-7xl">Motif Custom</span></h2>
              <p class="text-gray-400 text-sm sm:text-lg leading-relaxed max-w-md mx-auto lg:mx-0">Pilih motif eksklusif
                atau unggah desainmu sendiri dengan teknologi <span
                  class="text-white font-bold italic">Infinity-Print™</span>.</p>
              <button
                class="w-full sm:w-auto px-8 py-4 bg-white text-black rounded-full font-black uppercase text-[10px] sm:text-xs tracking-widest">
                Mulai Kustomisasi
              </button>
            </div>
          </div>
        </div>
      </section>

      <section id="collection" class="py-20 sm:py-32 px-4 sm:px-6 bg-[#f8f9fa]">
        <div class="max-w-7xl mx-auto text-center mb-12 sm:mb-20">
          <h2 class="text-3xl sm:text-5xl lg:text-6xl font-black italic uppercase tracking-tighter mb-4">Essential Drops
          </h2>
          <p class="text-gray-400 text-xs sm:text-sm font-medium">Koleksi dasar dengan kualitas tertinggi.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8 sm:gap-12">
          @foreach(['Endurance Tee' => '249k', 'Titan Shorts' => '299k', 'Armor Hoodie' => '549k'] as $name => $price)
            <div class="group">
              <div
                class="aspect-[3/4] bg-white rounded-[1.5rem] sm:rounded-[2.5rem] mb-6 relative overflow-hidden flex items-center justify-center shadow-sm hover:shadow-xl transition-all duration-500">
                <div
                  class="absolute inset-0 flex items-center justify-center font-black text-gray-50 text-6xl sm:text-8xl -rotate-12 select-none pointer-events-none uppercase">
                  {{ substr($name, 0, 1) }}
                </div>
                <button
                  class="absolute bottom-6 px-6 py-3 bg-black text-white rounded-xl text-[9px] font-bold uppercase tracking-widest opacity-100 lg:opacity-0 lg:group-hover:opacity-100 transition-all">Add
                  to Bag</button>
              </div>
              <div class="px-2 sm:px-4">
                <h4 class="font-black text-lg sm:text-xl tracking-tight uppercase italic">{{ $name }}</h4>
                <div class="flex justify-between items-center mt-2">
                  <p class="text-[8px] sm:text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em]">Eco-Poly
                    Material</p>
                  <span class="font-bold text-base sm:text-lg">Rp {{ $price }}</span>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </section>

      <footer class="py-12 sm:py-20 px-4 sm:px-6 bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12 border-b border-gray-100 pb-12 sm:pb-20">
          <div class="col-span-1 md:col-span-2 text-center md:text-left">
            <div class="text-2xl sm:text-3xl font-black italic tracking-tighter mb-6">K.O.C</div>
            <p class="max-w-xs mx-auto md:mx-0 text-gray-400 text-xs sm:text-sm leading-relaxed font-medium">
              Mendefinisikan ulang pakaian olahraga sejak 2024.</p>
          </div>
          <div class="text-center md:text-left space-y-4">
            <h5 class="text-[9px] sm:text-[10px] font-black uppercase tracking-[0.3em]">Links</h5>
            <ul class="text-xs sm:text-sm text-gray-500 space-y-2 uppercase font-bold tracking-widest">
              <li><a href="#" class="hover:text-black">Size Guide</a></li>
              <li><a href="#" class="hover:text-black">Custom</a></li>
            </ul>
          </div>
          <div class="text-center md:text-left space-y-4">
            <h5 class="text-[9px] sm:text-[10px] font-black uppercase tracking-[0.3em]">Social</h5>
            <div class="flex justify-center md:justify-start gap-4">
              <a href="#"
                class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center hover:bg-black hover:text-white transition-all italic font-black text-[10px]">Ig</a>
              <a href="#"
                class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center hover:bg-black hover:text-white transition-all italic font-black text-[10px]">Tw</a>
            </div>
          </div>
        </div>
        <div
          class="max-w-7xl mx-auto pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-center md:text-left">
          <p class="text-[8px] sm:text-[10px] font-bold uppercase tracking-widest text-gray-400">© 2026 K.O.C Athletics.
          </p>
          <div class="flex gap-6 sm:gap-8 text-[8px] sm:text-[10px] font-bold uppercase tracking-widest text-gray-400">
            <a href="#">Privacy</a>
            <a href="#">Terms</a>
          </div>
        </div>
      </footer>

    </div>
  </body>
</x-layouts.guest>