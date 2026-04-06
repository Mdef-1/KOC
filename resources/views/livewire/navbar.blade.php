<div x-data="{ 
    mobileMenuOpen: false,
    activeSection: '',
    isLandingPage: {{ request()->routeIs('home') ? 'true' : 'false' }}
}" 
x-init="initNavbar()">
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
                    <div class="text-xl sm:text-2xl font-black tracking-tighter italic">KOC</div>
                </a>
            </div>

            <div class="hidden md:flex items-center">
                {{-- Anchor Links (Smooth Scroll) - Only on Landing Page --}}
                @if(request()->routeIs('home'))
                    <div class="flex items-center gap-8 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 mr-8">
                        <a href="#katalog" 
                            :class="activeSection === 'katalog' ? 'text-black' : 'hover:text-black transition-colors'"
                            class="relative py-2">
                            Katalog
                            <span x-show="activeSection === 'katalog'" 
                                x-transition
                                class="absolute bottom-0 left-0 w-full h-0.5 bg-black"></span>
                        </a>
                        <a href="#cara-order" 
                            :class="activeSection === 'cara-order' ? 'text-black' : 'hover:text-black transition-colors'"
                            class="relative py-2">
                            Cara Order
                            <span x-show="activeSection === 'cara-order'" 
                                x-transition
                                class="absolute bottom-0 left-0 w-full h-0.5 bg-black"></span>
                        </a>
                        <a href="#custom" 
                            :class="activeSection === 'custom' ? 'text-black' : 'hover:text-black transition-colors'"
                            class="relative py-2">
                            Custom
                            <span x-show="activeSection === 'custom'" 
                                x-transition
                                class="absolute bottom-0 left-0 w-full h-0.5 bg-black"></span>
                        </a>
                    </div>
                @endif

                {{-- External Links with Visual Distinction --}}
                <div class="flex items-center gap-4">
                    {{-- Full Catalog Button (External Navigation) --}}
                    <a href="{{ route('catalog.index') }}" 
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-black text-white text-[10px] font-bold uppercase tracking-widest rounded-full hover:bg-neutral-800 transition-all group">
                        <span>Lihat Katalog</span>
                        <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>
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
                <div class="text-xl font-black italic tracking-tighter">KOC</div>
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
                <p class="text-[8px] font-bold text-gray-300 uppercase tracking-widest italic">© 2026 KOC Athletics
                </p>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function initNavbar() {
                if (this.isLandingPage) {
                    // Smooth scroll handler
                    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                        anchor.addEventListener('click', function (e) {
                            e.preventDefault();
                            const target = document.querySelector(this.getAttribute('href'));
                            if (target) {
                                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                            }
                        });
                    });

                    // Intersection Observer for active section
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                this.activeSection = entry.target.id;
                            }
                        });
                    }, { threshold: 0.3, rootMargin: '-100px 0px -50% 0px' });

                    ['katalog', 'cara-order', 'keunggulan', 'custom', 'order-now'].forEach(id => {
                        const el = document.getElementById(id);
                        if (el) observer.observe(el);
                    });
                }
            }

            document.addEventListener('livewire:initialized', () => {
                @this.on('logged-out', () => {
                    window.location.href = '{{ route('login') }}';
                });
            });
        </script>
    @endpush
</div>