<?php

use App\Livewire\CatalogPage;
use App\Livewire\Admin\CategoryTable;
use App\Livewire\Admin\ProductTable;
use App\Livewire\Admin\InquiryTable;
use App\Http\Controllers\Admin\DashboardController;
use App\Livewire\Admin\ProductGallery;
use App\Livewire\Admin\StockTransactionTable;
use App\Livewire\ProductDetailPage;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\InventoryTable;
use App\Livewire\Admin\UserTable;
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

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('inventory', InventoryTable::class)->name('admin.inventory.index');
    Volt::route('products', ProductTable::class)->name('admin.products.index');
    Volt::route('categories', CategoryTable::class)->name('admin.categories.index');
    Volt::route('inquiries', InquiryTable::class)->name('admin.inquiries.index');
    Volt::route('User', UserTable::class)->name('admin.user.index');
    Volt::route('product_gallery', ProductGallery::class)->name('admin.product_gallery.index');
    Volt::route('stock_transaction', StockTransactionTable::class)->name('admin.stock_transaction.index');
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


