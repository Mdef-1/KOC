<?php

use App\Http\Controllers\Api\CatalogController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Catalog
|--------------------------------------------------------------------------
|
| Endpoint untuk katalog produk publik
|
*/

// Public API Routes (no authentication required)
Route::prefix('catalog')->group(function () {
    
    // List produk dengan search, filter, sort, pagination
    // GET /api/catalog/products?search=xxx&category=xxx&sort_by=name&sort_order=asc&per_page=12
    Route::get('/products', [CatalogController::class, 'index']);
    
    // Featured products
    // GET /api/catalog/products/featured?limit=8
    Route::get('/products/featured', [CatalogController::class, 'featured']);
    
    // Detail produk by ID
    // GET /api/catalog/products/{id}
    Route::get('/products/{id}', [CatalogController::class, 'show']);
    
    // Detail produk by slug
    // GET /api/catalog/products/slug/{slug}
    Route::get('/products/slug/{slug}', [CatalogController::class, 'showBySlug']);
    
    // List kategori
    // GET /api/catalog/categories?with_count=true
    Route::get('/categories', [CatalogController::class, 'categories']);
    
    // Produk by category slug
    // GET /api/catalog/categories/{slug}/products
    Route::get('/categories/{slug}/products', [CatalogController::class, 'productsByCategory']);
    
});
