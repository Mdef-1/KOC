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

        $faker = \Faker\Factory::create('id_ID');
        $categories = Category::pluck('id');

        if ($categories->isEmpty()) {
            $this->command->warn('No categories found. Please run CategorySeeder first!');
            return;
        }

        for ($i = 1; $i <= 20; $i++) {
            $name = $faker->sentence(3);
            $slug = Str::slug($name);
            
            Product::create([
                'name' => $name,
                'slug' => $slug,
                'description' => $faker->paragraph(3),
                'category_id' => $faker->randomElement($categories),
                'is_active' => $faker->boolean(90) // 90% chance of being active
            ]);
        }

        $this->command->info('Products seeded successfully!');
    }
}
