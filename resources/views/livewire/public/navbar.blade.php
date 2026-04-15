<div x-data="{ 
    mobileMenuOpen: false,
    activeSection: '',
    isLandingPage: {{ request()->routeIs('home') ? 'true' : 'false' }}
}" 
x-init="initNavbar()">
    <nav class="fixed top-0 w-full z-[100] bg-white/80 backdrop-blur-xl border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 sm:h-20 flex items-center justify-between relative">

            <div class="flex md:hidden">
                <button type="button"
                    @click="mobileMenuOpen = true"
                    class="p-2 -ml-2 rounded-lg text-gray-600 hover:bg-gray-100 active:bg-gray-200 transition-colors cursor-pointer touch-manipulation">
                    <svg class="w-6 h-6 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <a href="#keunggulan" 
                            :class="activeSection === 'keunggulan' ? 'text-black' : 'hover:text-black transition-colors'"
                            class="relative py-2">
                            Keunggulan
                            <span x-show="activeSection === 'keunggulan'" 
                                x-transition
                                class="absolute bottom-0 left-0 w-full h-0.5 bg-black"></span>
                        </a>
                        <a href="#faq" 
                            :class="activeSection === 'faq' ? 'text-black' : 'hover:text-black transition-colors'"
                            class="relative py-2">
                            FAQ
                            <span x-show="activeSection === 'faq'" 
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

            <div class="flex items-center justify-between mb-12 pb-4 border-b border-gray-100">
                <div class="text-xl font-black italic tracking-tighter">KOC</div>
                <button type="button"
                    @click="mobileMenuOpen = false"
                    class="p-2 -mr-2 rounded-lg text-gray-400 hover:bg-gray-100 active:bg-gray-200 transition-colors cursor-pointer touch-manipulation">
                    <svg class="w-6 h-6 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="flex flex-col gap-2 text-sm font-semibold">
                <a href="{{ route('home') }}" @click="mobileMenuOpen = false"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 active:bg-gray-100 transition-colors cursor-pointer touch-manipulation">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Home
                </a>
                <a href="#katalog" @click="mobileMenuOpen = false"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 active:bg-gray-100 transition-colors cursor-pointer touch-manipulation">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    Katalog
                </a>
                <a href="#cara-order" @click="mobileMenuOpen = false"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 active:bg-gray-100 transition-colors cursor-pointer touch-manipulation">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    Cara Order
                </a>
                <a href="#keunggulan" @click="mobileMenuOpen = false"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 active:bg-gray-100 transition-colors cursor-pointer touch-manipulation">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                    Keunggulan
                </a>
                <a href="#faq" @click="mobileMenuOpen = false"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 active:bg-gray-100 transition-colors cursor-pointer touch-manipulation">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    FAQ
                </a>
            </nav>

            {{-- CTA to Full Catalog --}}
            <div class="mt-8 mb-8">
                <a href="{{ route('catalog.index') }}" @click="mobileMenuOpen = false"
                   class="flex items-center justify-center gap-3 w-full py-4 bg-gray-900 text-white rounded-2xl text-xs font-bold uppercase tracking-widest hover:bg-gray-800 transition-colors">
                    <span>Lihat Semua Produk</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>

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

                    ['katalog', 'cara-order', 'keunggulan', 'faq'].forEach(id => {
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