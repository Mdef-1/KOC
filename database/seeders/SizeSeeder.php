<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Size;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SizeSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('sizes')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get categories
        $fashion = Category::where('slug', 'fashion')->first();
        $elektronik = Category::where('slug', 'elektronik')->first();
        $olahraga = Category::where('slug', 'olahraga')->first();

        // Fashion sizes (S, M, L, XL, XXL)
        if ($fashion) {
            $fashionSizes = [
                ['label' => 'S', 'width' => 48, 'length' => 68, 'extra_price' => 0],
                ['label' => 'M', 'width' => 50, 'length' => 70, 'extra_price' => 0],
                ['label' => 'L', 'width' => 52, 'length' => 72, 'extra_price' => 10000],
                ['label' => 'XL', 'width' => 54, 'length' => 74, 'extra_price' => 15000],
                ['label' => 'XXL', 'width' => 56, 'length' => 76, 'extra_price' => 20000],
            ];

            foreach ($fashionSizes as $size) {
                Size::create(array_merge($size, ['category_id' => $fashion->id]));
            }
        }

        // Electronics sizes (Universal, Small, Large)
        if ($elektronik) {
            $electronicSizes = [
                ['label' => 'Universal', 'width' => 0, 'length' => 0, 'extra_price' => 0],
                ['label' => 'Mini', 'width' => 10, 'length' => 15, 'extra_price' => 0],
                ['label' => 'Standard', 'width' => 15, 'length' => 20, 'extra_price' => 50000],
            ];

            foreach ($electronicSizes as $size) {
                Size::create(array_merge($size, ['category_id' => $elektronik->id]));
            }
        }

        // Sports sizes
        if ($olahraga) {
            $sportsSizes = [
                ['label' => 'S', 'width' => 46, 'length' => 66, 'extra_price' => 0],
                ['label' => 'M', 'width' => 49, 'length' => 69, 'extra_price' => 0],
                ['label' => 'L', 'width' => 52, 'length' => 72, 'extra_price' => 10000],
                ['label' => 'XL', 'width' => 55, 'length' => 75, 'extra_price' => 15000],
            ];

            foreach ($sportsSizes as $size) {
                Size::create(array_merge($size, ['category_id' => $olahraga->id]));
            }
        }

        // Default sizes for other categories
        $otherCategories = Category::whereNotIn('slug', ['fashion', 'elektronik', 'olahraga'])->get();
        foreach ($otherCategories as $category) {
            Size::create([
                'category_id' => $category->id,
                'label' => 'Standard',
                'width' => 0,
                'length' => 0,
                'extra_price' => 0,
            ]);
        }

        $this->command->info('Sizes seeded successfully!');
    }
}
