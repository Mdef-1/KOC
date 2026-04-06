<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CatalogController extends Controller
{
    /**
     * List produk dengan pagination, search, filter, dan sorting
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::with(['category', 'gallery'])->active();

        // Search by name or description
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category slug
        if ($request->has('category') && $request->category) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by category_id
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter featured products
        if ($request->has('featured') && $request->featured) {
            $query->where('is_featured', true);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSortFields = ['id', 'name', 'view_count', 'order_count', 'created_at'];
        
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder === 'asc' ? 'asc' : 'desc');
        }

        // Pagination
        $perPage = $request->get('per_page', 12);
        $perPage = min($perPage, 100); // Max 100 per page

        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $this->formatProducts($products),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
            'links' => [
                'first' => $products->url(1),
                'last' => $products->url($products->lastPage()),
                'prev' => $products->previousPageUrl(),
                'next' => $products->nextPageUrl(),
            ]
        ]);
    }

    /**
     * Detail produk by ID
     */
    public function show($id): JsonResponse
    {
        $product = Product::with(['category', 'gallery'])->active()->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        // Increment view count
        $product->increment('view_count');

        return response()->json([
            'success' => true,
            'data' => $this->formatProductDetail($product)
        ]);
    }

    /**
     * Detail produk by slug
     */
    public function showBySlug($slug): JsonResponse
    {
        $product = Product::with(['category', 'gallery'])->active()->where('slug', $slug)->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        // Increment view count
        $product->increment('view_count');

        return response()->json([
            'success' => true,
            'data' => $this->formatProductDetail($product)
        ]);
    }

    /**
     * List kategori
     */
    public function categories(Request $request): JsonResponse
    {
        $query = Category::query();

        // Include product count
        if ($request->has('with_count') && $request->with_count) {
            $query->withCount(['products' => function ($q) {
                $q->where('is_active', true);
            }]);
        }

        $categories = $query->get();

        return response()->json([
            'success' => true,
            'data' => $categories->map(function ($category) use ($request) {
                $data = [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                ];

                if ($request->has('with_count') && $request->with_count) {
                    $data['products_count'] = $category->products_count;
                }

                return $data;
            })
        ]);
    }

    /**
     * Produk by category
     */
    public function productsByCategory($slug, Request $request): JsonResponse
    {
        $category = Category::where('slug', $slug)->first();

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan'
            ], 404);
        }

        $query = Product::with(['category', 'gallery'])
            ->where('category_id', $category->id)
            ->active();

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSortFields = ['id', 'name', 'view_count', 'order_count'];
        
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder === 'asc' ? 'asc' : 'desc');
        }

        $perPage = $request->get('per_page', 12);
        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
            ],
            'data' => $this->formatProducts($products),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ]
        ]);
    }

    /**
     * Featured products
     */
    public function featured(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 8);
        $limit = min($limit, 50);

        $products = Product::with(['category', 'gallery'])
            ->featured()
            ->active()
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $this->formatProducts($products)
        ]);
    }

    /**
     * Format product collection
     */
    private function formatProducts($products)
    {
        return $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'description' => $product->description,
                'category' => $product->category ? [
                    'id' => $product->category->id,
                    'name' => $product->category->name,
                    'slug' => $product->category->slug,
                ] : null,
                'is_featured' => $product->is_featured,
                'view_count' => $product->view_count,
                'order_count' => $product->order_count,
                'images' => $this->formatImages($product->gallery),
                'primary_image' => $this->getPrimaryImage($product),
            ];
        });
    }

    /**
     * Format single product detail
     */
    private function formatProductDetail($product)
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'description' => $product->description,
            'category' => $product->category ? [
                'id' => $product->category->id,
                'name' => $product->category->name,
                'slug' => $product->category->slug,
                'description' => $product->category->description,
            ] : null,
            'is_featured' => $product->is_featured,
            'is_active' => $product->is_active,
            'view_count' => $product->view_count,
            'order_count' => $product->order_count,
            'featured_order' => $product->featured_order,
            'images' => $this->formatImages($product->gallery),
            'primary_image' => $this->getPrimaryImage($product),
        ];
    }

    /**
     * Format images
     */
    private function formatImages($gallery)
    {
        if (!$gallery || $gallery->isEmpty()) {
            return [];
        }

        return $gallery->map(function ($item) {
            $url = $item->image_url;
            
            // Handle storage path
            if ($url && !str_starts_with($url, 'http')) {
                $url = asset('storage/' . ltrim($url, 'storage/'));
            }

            return [
                'id' => $item->id,
                'url' => $url,
                'sort_order' => $item->sort_order ?? 0,
            ];
        });
    }

    /**
     * Get primary image
     */
    private function getPrimaryImage($product)
    {
        $galleryItem = $product->gallery->first();
        
        if (!$galleryItem) {
            return null;
        }

        $url = $galleryItem->image_url;
        
        if ($url && !str_starts_with($url, 'http')) {
            $url = asset('storage/' . ltrim($url, 'storage/'));
        }

        return $url;
    }
}
