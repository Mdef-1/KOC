<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Inquiry;
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
            'totalInquiries' => Inquiry::count(),
        ];

        $recentProducts = Product::orderByDesc('id')->take(5)->get();
        $recentInquiries = Inquiry::with('product')->orderByDesc('id')->take(5)->get();
        $userName = optional(auth()->user())->name;

        return view('dashboard', [
            'stats' => $stats,
            'recentProducts' => $recentProducts,
            'recentInquiries' => $recentInquiries,
            'userName' => $userName,
        ]);
    }
}
