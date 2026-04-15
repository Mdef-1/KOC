<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" >

<head>
    @include('partials.head')
    @livewireStyles
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
            <x-app-logo />
        </a>

        <flux:navlist variant="outline">
            <flux:navlist.group :heading="__('Platform')" class="grid">
                <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                    wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
            </flux:navlist.group>

            <flux:navlist.group :heading="__('Back-Office')" class="grid">
                {{-- Ikon Users: Orang/Grup --}}
                <flux:navlist.item icon="users" :href="route('admin.user.index')"
                    :current="request()->routeIs('admin.user.*')" wire:navigate>Users</flux:navlist.item>

                {{-- Ikon Inventory: Box/Gudang --}}
                <flux:navlist.item icon="archive-box" :href="route('admin.inventory.index')"
                    :current="request()->routeIs('admin.inventory.*')" wire:navigate>Inventory</flux:navlist.item>

                {{-- Ikon Products: Tag/Shopping Bag --}}
                <flux:navlist.item icon="shopping-bag" :href="route('admin.products.index')"
                    :current="request()->routeIs('admin.products.*')" wire:navigate>Products</flux:navlist.item>

                {{-- Ikon Categories: Folder/Squares --}}
                <flux:navlist.item icon="squares-2x2" :href="route('admin.categories.index')"
                    :current="request()->routeIs('admin.categories.*')" wire:navigate>Categories</flux:navlist.item>

                {{-- Ikon Materials: Swatch/Color Palette --}}
                <flux:navlist.item icon="swatch" :href="route('admin.materials.index')"
                    :current="request()->routeIs('admin.materials.*')" wire:navigate>Materials</flux:navlist.item>

                {{-- Ikon Sizes: Ruler/Scale --}}
                <flux:navlist.item icon="scale" :href="route('admin.sizes.index')"
                    :current="request()->routeIs('admin.sizes.*')" wire:navigate>Sizes</flux:navlist.item>

                {{-- Ikon Orders: Shopping Cart --}}
                <flux:navlist.item icon="shopping-cart" :href="route('admin.orders.index')"
                    :current="request()->routeIs('admin.orders.*')" wire:navigate>Orders</flux:navlist.item>

                {{-- Ikon Product Gallery: Photo/Camera --}}
                <flux:navlist.item icon="photo" :href="route('admin.product_gallery.index')"
                    :current="request()->routeIs('admin.product_gallery.*')" wire:navigate>Product Gallery
                </flux:navlist.item>

                {{-- Ikon Stock Transaction: Arrows Up/Down (Transfer) --}}
                <flux:navlist.item icon="arrows-right-left" :href="route('admin.stock_transaction.index')"
                    :current="request()->routeIs('admin.stock_transaction.*')" wire:navigate>Stock Transaction
                </flux:navlist.item>

                {{-- Ikon Featured Products: Star --}}
                <flux:navlist.item icon="star" :href="route('admin.featured_products.index')"
                    :current="request()->routeIs('admin.featured_products.*')" wire:navigate>Featured Products
                </flux:navlist.item>
            </flux:navlist.group>
        </flux:navlist>

        <flux:spacer />


        <!-- Desktop User Menu -->
        <flux:dropdown class="hidden lg:block" position="bottom" align="start">
            <flux:profile :name="auth()->user()->name" :initials="auth()->user()->initials()"
                icon:trailing="chevron-up-down" data-test="sidebar-menu-button" />

            <flux:menu class="w-[220px]">
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full"
                        data-test="logout-button">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    <!-- Mobile Header -->
    <flux:header class="lg:hidden border-b border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900 sticky top-0 z-40">
        <div class="flex items-center justify-between w-full px-4 py-3">
            <!-- Left: Menu Toggle -->
            <flux:sidebar.toggle class="lg:hidden p-2 -ml-2 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors cursor-pointer touch-manipulation" icon="bars-3" />

            <!-- Center: Logo/Title -->
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2" wire:navigate>
                <x-app-logo class="h-8 w-auto" />
                <span class="font-semibold text-zinc-800 dark:text-zinc-100 text-sm">KOC Admin</span>
            </a>

            <!-- Right: Profile -->
            <flux:dropdown position="top" align="end">
                <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" class="p-2 -mr-2 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors cursor-pointer touch-manipulation" />

                <flux:menu class="w-[260px]">
                    <!-- User Info -->
                    <div class="px-4 py-3 border-b border-zinc-100 dark:border-zinc-700">
                        <div class="flex items-center gap-3">
                            <span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white">
                                <span class="flex h-full w-full items-center justify-center font-semibold text-sm">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400 truncate">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="p-2">
                        <flux:menu.item :href="route('profile.edit')" icon="user" wire:navigate class="rounded-lg">
                            Profil Saya
                        </flux:menu.item>
                        <flux:menu.item :href="route('dashboard')" icon="home" wire:navigate class="rounded-lg">
                            Dashboard
                        </flux:menu.item>
                    </div>

                    <flux:menu.separator />

                    <!-- Logout -->
                    <div class="p-2">
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20" data-test="logout-button">
                                {{ __('Log Out') }}
                            </flux:menu.item>
                        </form>
                    </div>
                </flux:menu>
            </flux:dropdown>
        </div>
    </flux:header>

    {{ $slot }}

    @fluxScripts
    @livewireScripts
</body>

</html>