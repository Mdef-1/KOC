<?php

namespace Database\Seeders;

use App\Models\Inquiry;
use App\Models\Product;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InquirySeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('inquiries')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = Factory::create('id_ID');
        $products = Product::pluck('id');
        $statuses = ['new', 'pending', 'in_progress', 'resolved', 'cancelled'];

        if ($products->isEmpty()) {
            $this->command->warn('No products found. Please run ProductSeeder first!');
            return;
        }

        for ($i = 0; $i < 15; $i++) {
            Inquiry::create([
                'customer_name' => $faker->name,
                'customer_contact' => $faker->email . ' | ' . $faker->phoneNumber,
                'product_id' => $faker->randomElement($products),
                'message' => $faker->paragraph(3),
                'status' => $faker->randomElement($statuses),
                'created_at' => $faker->dateTimeBetween('-3 months', 'now')
            ]);
        }

        $this->command->info('Inquiries seeded successfully!');
    }
}
