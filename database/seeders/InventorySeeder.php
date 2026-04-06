<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('inventory')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get all products with their categories
        $products = Product::with('category')->get();
        $sizes = Size::all();

        if ($products->isEmpty()) {
            $this->command->warn('No products found. Please run ProductSeeder first!');
            return;
        }

        if ($sizes->isEmpty()) {
            $this->command->warn('No sizes found. Please create sizes first!');
            return;
        }

        // Sample warehouse locations
        $warehouses = ['Rak-A1', 'Rak-A2', 'Rak-B1', 'Rak-B2', 'Gudang-Utama'];

        foreach ($products as $product) {
            // Get sizes for this product's category
            $categorySizes = $sizes->where('category_id', $product->category_id);
            
            // If no sizes for this category, use first 3 sizes
            if ($categorySizes->isEmpty()) {
                $categorySizes = $sizes->take(3);
            }
            
            // Create inventory for each size (2-4 sizes per product)
            $selectedSizes = $categorySizes->random(min(rand(2, 4), $categorySizes->count()));
            
            foreach ($selectedSizes as $index => $size) {
                $sku = strtoupper(substr(str_replace(' ', '-', $product->name), 0, 3)) . '-' . 
                       strtoupper($size->label) . '-' . 
                       str_pad($product->id, 3, '0', STR_PAD_LEFT);
                
                // Base price (without extra price from size)
                $basePrice = rand(150000, 400000);
                
                Inventory::create([
                    'product_id' => $product->id,
                    'sizes_id' => $size->id,
                    'sku' => $sku,
                    'stock' => rand(5, 100),
                    'price' => $basePrice,  // Base price
                    'cost_price' => rand(80000, $basePrice - 20000),  // Cost price
                    'warehouse_location' => $warehouses[array_rand($warehouses)],
                    'last_updated' => now()->subDays(rand(0, 30)),
                ]);
            }
        }

        $this->command->info('Inventory seeded successfully with multiple sizes per product!');
    }
}
