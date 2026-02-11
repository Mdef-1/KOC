<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductGallery;
use App\Models\ProductGalleryModel;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductGallerySeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('product_gallery')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = Factory::create();
        $products = Product::pluck('id');

        if ($products->isEmpty()) {
            $this->command->warn('No products found. Please run ProductSeeder first!');
            return;
        }

        foreach ($products as $productId) {
            // Each product gets 1-3 gallery images
            $imageCount = rand(1, 3);

            // CARA 2: Jika kamu tetap ingin pakai pluck (Lebih hemat memori)
            $productIds = \App\Models\Product::pluck('id'); // Menghasilkan [1, 2, 3...]

            foreach ($productIds as $id) {
                foreach (range(0, 2) as $i) {
                    ProductGalleryModel::create([
                        'product_id' => $id, // Langsung pakai variabel $id karena dia sudah integer
                        'image_url' => "https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=800&auto=format&fit=crop&sig=" . $id . $i,
                        'is_primary' => $i === 0,
                        'sort_order' => $i
                    ]);
                }
            }
        }

        $this->command->info('Product galleries seeded successfully!');
    }
}
