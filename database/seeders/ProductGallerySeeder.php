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
            
            for ($i = 0; $i < $imageCount; $i++) {
                ProductGalleryModel::create([
                    'product_id' => $productId,
                    'image_url' => $faker->imageUrl(800, 600, 'products', true, 'product-' . $productId),
                    'is_primary' => $i === 0, // First image is primary
                    'sort_order' => $i
                ]);
            }
        }

        $this->command->info('Product galleries seeded successfully!');
    }
}
