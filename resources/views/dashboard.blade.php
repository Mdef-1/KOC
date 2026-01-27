<x-layouts.app :title="__('Dashboard')">
    <div class="p-6">
        <!-- Welcome -->
        <div class="mb-6">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl p-6 text-white flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">Selamat datang{{ isset($userName) && $userName ? ", $userName" : '' }} 👋</h1>
                    <p class="text-blue-100 mt-1">Ringkasan aktivitas dan pintasan cepat untuk mengelola katalog.</p>
                </div>
            </div>
        </div>

        <!-- Quick actions -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
            <a href="{{ route('admin.products.index') }}" class="group bg-white border rounded-lg p-4 hover:shadow-md transition">
                <div class="text-indigo-600 font-medium">Produk</div>
                <div class="text-gray-500 text-sm">Kelola produk</div>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="group bg-white border rounded-lg p-4 hover:shadow-md transition">
                <div class="text-indigo-600 font-medium">Kategori</div>
                <div class="text-gray-500 text-sm">Kelola kategori</div>
            </a>
            <a href="{{ route('admin.inventory.index') }}" class="group bg-white border rounded-lg p-4 hover:shadow-md transition">
                <div class="text-indigo-600 font-medium">Inventori</div>
                <div class="text-gray-500 text-sm">Stok & harga</div>
            </a>
            <a href="{{ route('admin.inquiries.index') }}" class="group bg-white border rounded-lg p-4 hover:shadow-md transition">
                <div class="text-indigo-600 font-medium">Inquiries</div>
                <div class="text-gray-500 text-sm">Pertanyaan pelanggan</div>
            </a>
            <a href="{{ route('admin.product_gallery.index') }}" class="group bg-white border rounded-lg p-4 hover:shadow-md transition">
                <div class="text-indigo-600 font-medium">Galeri</div>
                <div class="text-gray-500 text-sm">Gambar produk</div>
            </a>
            <a href="{{ route('admin.user.index') }}" class="group bg-white border rounded-lg p-4 hover:shadow-md transition">
                <div class="text-indigo-600 font-medium">Pengguna</div>
                <div class="text-gray-500 text-sm">Kelola akses</div>
            </a>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Users Card -->
            <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Total Users</h3>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['totalUsers'] }}</p>
                </div>
                <div class="text-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.124-1.28-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.124-1.28.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>

            <!-- Total Products Card -->
            <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Total Products</h3>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['totalProducts'] }}</p>
                </div>
                <div class="text-green-500">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                </div>
            </div>

            <!-- Total Categories Card -->
            <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Total Categories</h3>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['totalCategories'] }}</p>
                </div>
                <div class="text-yellow-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>

            <!-- Total Inquiries Card -->
            <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Total Inquiries</h3>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['totalInquiries'] }}</p>
                </div>
                <div class="text-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Recent -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            <!-- Recent Products -->
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold text-gray-800">Produk Terbaru</h3>
                    <a href="{{ route('admin.products.index') }}" class="text-sm text-indigo-600 hover:underline">Lihat semua</a>
                </div>
                <ul class="divide-y">
                    @forelse(($recentProducts ?? []) as $product)
                        <li class="py-3 flex items-center justify-between">
                            <div class="min-w-0">
                                <div class="font-medium text-gray-900 truncate">{{ $product->name }}</div>
                                <div class="text-xs text-gray-500">ID: {{ $product->id }}</div>
                            </div>
                            <span class="text-xs px-2 py-1 rounded {{ ($product->is_active ?? false) ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ ($product->is_active ?? false) ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </li>
                    @empty
                        <li class="py-6 text-center text-gray-500">Belum ada produk.</li>
                    @endforelse
                </ul>
            </div>

            <!-- Recent Inquiries -->
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold text-gray-800">Inquiry Terbaru</h3>
                    <a href="{{ route('admin.inquiries.index') }}" class="text-sm text-indigo-600 hover:underline">Lihat semua</a>
                </div>
                <ul class="divide-y">
                    @forelse(($recentInquiries ?? []) as $inq)
                        <li class="py-3">
                            <div class="flex items-start justify-between">
                                <div class="min-w-0">
                                    <div class="font-medium text-gray-900 truncate">{{ $inq->customer_name }}</div>
                                    <div class="text-xs text-gray-500 truncate">{{ $inq->product->name ?? 'Tanpa produk' }}</div>
                                </div>
                                <span class="text-xs px-2 py-1 rounded bg-blue-100 text-blue-700">{{ ucfirst($inq->status ?? 'baru') }}</span>
                            </div>
                            @if(!empty($inq->message))
                                <div class="mt-1 text-sm text-gray-600 truncate">{{ $inq->message }}</div>
                            @endif
                        </li>
                    @empty
                        <li class="py-6 text-center text-gray-500">Belum ada inquiry.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-layouts.app>
