<div>
    <nav
        class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-6 py-4 bg-white/80 backdrop-blur-sm border-b border-gray-200/50 transition-all duration-300 hover:bg-white/90">
        <a href="{{ url('/') }}" class="font-bold text-lg">
            K.O.C
        </a>

        {{-- Desktop --}}
        <div class="hidden md:flex items-center gap-6">
            <a href="{{ route(name: 'catalog.index') }}" class="text-sm font-medium text-gray-600 hover:text-pink-500">
                Katalog produk
            </a>
        </div>



        {{-- Mobile Menu Button --}}
        <div class="md:hidden">
            <button type="button" class="text-gray-500 hover:text-gray-600" x-data="{ open: false }"
                @click="open = !open">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16m-7 6h7" />
                    <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" style="display: none;" />
                </svg>
            </button>
        </div>

        {{-- Mobile Menu --}}
        <div class="md:hidden absolute top-16 left-0 right-0 bg-white shadow-lg py-2 z-50" x-show="open"
            @click.away="open = false" x-transition>
            <a href="{{ route('dashboard') }}" class="block px-6 py-3 text-gray-700 hover:bg-gray-50">
                Dashboard
            </a>
        </div>
    </nav>

    <!-- Add padding to the top of the main content to account for fixed navbar -->
    <div class="h-16"></div>

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