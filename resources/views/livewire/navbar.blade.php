<div x-data="{ mobileMenuOpen: false }">
    <nav class="fixed top-0 w-full z-[100] bg-white/80 backdrop-blur-xl border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 sm:h-20 flex items-center justify-between relative">

            <div class="flex md:hidden">
                <button @click="mobileMenuOpen = true" class="text-gray-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
            </div>

            <div class="absolute left-1/2 -translate-x-1/2 md:static md:translate-x-0">
                <a href="{{ route('home') }}">
                    <div class="text-xl sm:text-2xl font-black tracking-tighter italic">K.O.C</div>
                </a>
            </div>

            <div
                class="hidden md:flex items-center gap-10 text-[10px] font-bold uppercase tracking-[0.3em] text-gray-400">
                <a href="{{ route('catalog.index') }}"
                    class="hover:text-black transition-colors {{ request()->routeIs('catalog.index') ? 'text-black' : '' }}">Katalog</a>
                <a href="#" class="hover:text-black transition-colors">Periksa Size</a>
            </div>

            <div class="flex items-center">
                <button
                    class="px-4 sm:px-6 py-2 bg-black text-white text-[9px] sm:text-[10px] font-bold uppercase tracking-widest rounded-full hover:scale-105 transition-transform">
                    <span class="hidden sm:inline">Start Design</span>
                    <span class="sm:hidden italic">Design</span>
                </button>
            </div>
        </div>
    </nav>

    <div x-show="mobileMenuOpen" class="fixed inset-0 z-[110] md:hidden" style="display: none;">

        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="mobileMenuOpen = false"
            class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="absolute top-0 left-0 w-[80%] max-w-sm h-full bg-white shadow-2xl p-8 flex flex-col">

            <div class="flex items-center justify-between mb-16">
                <div class="text-xl font-black italic tracking-tighter">K.O.C</div>
                <button @click="mobileMenuOpen = false" class="text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="flex flex-col gap-8 text-xs font-black uppercase tracking-[0.4em]">
                <a href="{{ route('home') }}"
                    class="py-2 border-b border-gray-50 hover:text-gray-500 transition-colors">Home</a>
                <a href="{{ route('catalog.index') }}"
                    class="py-2 border-b border-gray-50 hover:text-gray-500 transition-colors">Katalog</a>
                <a href="#" class="py-2 border-b border-gray-50 hover:text-gray-500 transition-colors">Periksa Size</a>
                <a href="#" class="py-2 border-b border-gray-50 hover:text-gray-500 transition-colors">Custom Gear</a>
            </nav>

            <div class="mt-auto">
                <p class="text-[8px] font-bold text-gray-300 uppercase tracking-widest italic">© 2026 K.O.C Athletics
                </p>
            </div>
        </div>
    </div>

    <div class="h-16 sm:h-20"></div>

    @push('scripts')
        <script>
            document.addEventListener('livewire:initialized', () => {
                @this.on('logged-out', () => {
                    window.location.href = '{{ route('login') }}';
                });
            });
        </script>
    @endpush
</div>