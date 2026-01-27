<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\Product;
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

        // Get all product IDs
        $productIds = Product::pluck('id');

        if ($productIds->isEmpty()) {
            $this->command->warn('No products found. Please run ProductSeeder first!');
            return;
        }

        // Sample warehouse locations
        $warehouses = ['Main Warehouse', 'East Warehouse', 'West Warehouse', 'North Warehouse', 'South Warehouse'];

        foreach ($productIds as $productId) {
            Inventory::create([
                'product_id' => $productId,
                'sku' => 'SKU-' . strtoupper(\Illuminate\Support\Str::random(8)),
                'stock' => rand(5, 100),
                'price' => rand(500000, 5000000),  // Price in IDR
                'cost_price' => rand(300000, 4000000),  // Cost price in IDR
                'warehouse_location' => $warehouses[array_rand($warehouses)],
            ]);
        }

        $this->command->info('Inventory seeded successfully!');
    }
}
