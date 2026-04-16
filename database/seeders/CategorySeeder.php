<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categories = [
            ['name' => 'Kaos Polos', 'slug' => 'kaos-polos', 'description' => 'Kaos polos tanpa sablon, ready stock'],
            ['name' => 'Kaos Raglan', 'slug' => 'kaos-raglan', 'description' => 'Kaos raglan lengan panjang/pendek'],
            ['name' => 'Polo Shirt', 'slug' => 'polo-shirt', 'description' => 'Kaos berkerah dengan kancing'],
            ['name' => 'Hoodie & Sweater', 'slug' => 'hoodie-sweater', 'description' => 'Hoodie dan sweater polos'],
            ['name' => 'Kaos V-Neck', 'slug' => 'kaos-v-neck', 'description' => 'Kaos kerah V'],
            ['name' => 'Long Sleeve', 'slug' => 'long-sleeve', 'description' => 'Kaos lengan panjang'],
            ['name' => 'Tank Top', 'slug' => 'tank-top', 'description' => 'Kaos tanpa lengan'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('Categories seeded successfully!');
    }
}
