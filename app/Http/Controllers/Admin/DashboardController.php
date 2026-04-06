<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'totalUsers' => User::count(),
            'totalProducts' => Product::count(),
            'totalCategories' => Category::count(),
            'totalOrders' => Order::count(),
            'pendingOrders' => Order::where('status', 'pending')->count(),
        ];

        $recentProducts = Product::orderByDesc('id')->take(5)->get();
        $recentOrders = Order::with('product')->orderByDesc('id')->take(5)->get();
        $userName = optional(auth()->user())->name;

        // Analytics
        $mostOrderedProducts = Product::orderByDesc('order_count')
            ->orderByDesc('view_count')
            ->with('category')
            ->take(5)
            ->get();

        $mostVisitedProducts = Product::orderByDesc('view_count')
            ->orderByDesc('order_count')
            ->with('category')
            ->take(5)
            ->get();

        return view('dashboard', [
            'stats' => $stats,
            'recentProducts' => $recentProducts,
            'recentOrders' => $recentOrders,
            'mostOrderedProducts' => $mostOrderedProducts,
            'mostVisitedProducts' => $mostVisitedProducts,
            'userName' => $userName,
        ]);
    }
}
