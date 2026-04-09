<?php

use App\Livewire\Public\CatalogPage;
use App\Livewire\Public\OrderBuilder;
use App\Livewire\Public\OrderTracking;
use App\Livewire\Public\ProductDetailPage;
use App\Livewire\Admin\CategoryTable;
use App\Livewire\Admin\SizeTable;
use App\Livewire\Admin\ProductTable;
use App\Livewire\Admin\OrderTable;
use App\Http\Controllers\Admin\DashboardController;
use App\Livewire\Admin\ProductGallery;
use App\Livewire\Admin\StockTransactionTable;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\InventoryTable;
use App\Livewire\Admin\UserTable;
use App\Livewire\Admin\FeaturedProducts;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('landing');
})->name('home');

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

route::get('/product/{id}', ProductDetailPage::class)->name('product.detail');

Volt::route('catalog', CatalogPage::class)->name('catalog.index');
Volt::route('order-builder', OrderBuilder::class)->name('order.builder');
Volt::route('track-order', OrderTracking::class)->name('order.tracking');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('inventory', InventoryTable::class)->name('admin.inventory.index');
    Volt::route('products', ProductTable::class)->name('admin.products.index');
    Volt::route('categories', CategoryTable::class)->name('admin.categories.index');
    Volt::route('sizes', SizeTable::class)->name('admin.sizes.index');
    Volt::route('orders', OrderTable::class)->name('admin.orders.index');
    Volt::route('User', UserTable::class)->name('admin.user.index');
    Volt::route('product_gallery', ProductGallery::class)->name('admin.product_gallery.index');
    Volt::route('stock_transaction', StockTransactionTable::class)->name('admin.stock_transaction.index');
    Volt::route('featured_products', FeaturedProducts::class)->name('admin.featured_products.index');
    Volt::route('settings/two-factor', 'settings.two-factor')

        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});


