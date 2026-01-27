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
            ['name' => 'Elektronik', 'slug' => 'elektronik', 'description' => 'Kategori untuk produk-produk elektronik'],
            ['name' => 'Fashion', 'slug' => 'fashion', 'description' => 'Kategori untuk produk-produk fashion'],
            ['name' => 'Kecantikan', 'slug' => 'kecantikan', 'description' => 'Kategori untuk produk-produk kecantikan'],
            ['name' => 'Rumah Tangga', 'slug' => 'rumah-tangga', 'description' => 'Kategori untuk peralatan rumah tangga'],
            ['name' => 'Olahraga', 'slug' => 'olahraga', 'description' => 'Kategori untuk peralatan olahraga'],
            ['name' => 'Mainan', 'slug' => 'mainan', 'description' => 'Kategori untuk mainan anak-anak'],
            ['name' => 'Makanan & Minuman', 'slug' => 'makanan-minuman', 'description' => 'Kategori untuk produk makanan dan minuman'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('Categories seeded successfully!');
    }
}
