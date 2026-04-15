<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('products')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categories = Category::pluck('id');

        if ($categories->isEmpty()) {
            $this->command->warn('No categories found. Please run CategorySeeder first!');
            return;
        }

        $products = [
            'Kaos Polos Cotton', 'Kaos Raglan Premium', 'Polo Shirt Kerah', 'Sweater Hoodie Polos',
            'Jaket Varsity', 'Kaos V-Neck', 'Kaos Long Sleeve', 'Tank Top Polos',
            'Kaos Anak Karakter', 'Kemeja Flanel', 'Kaos Oversized', 'Crop Top Polos',
            'Kaos Sport Dry Fit', 'Seragam Komunitas', 'Kaos Family Bundle', 'Jersey Futsal',
            'Kaos Sablon Custom', 'Jaket Bomber', 'Sweater Crew Neck', 'Kaos Muscle Fit'
        ];

        foreach ($products as $i => $name) {
            Product::create([
                'name' => $name,
                'slug' => Str::slug($name) . '-' . ($i + 1),
                'description' => 'Produk berkualitas dengan bahan premium, nyaman dipakai sehari-hari.',
                'category_id' => $categories->random(),
                'is_active' => true
            ]);
        }

        $this->command->info('Products seeded successfully!');
    }
}
