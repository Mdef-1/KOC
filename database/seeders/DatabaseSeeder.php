<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Note: Create users manually via tinker or register form
        // Users table doesn't have email/customer_contact column

        // Run seeders in order
        $this->call([
            CategorySeeder::class,
            SizeSeeder::class,
            ProductSeeder::class,
            InventorySeeder::class,
        ]);
    }
}
