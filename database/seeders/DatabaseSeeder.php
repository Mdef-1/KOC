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
        // Create admin user (using customer_contact as unique identifier)
        User::firstOrCreate(
            ['customer_contact' => 'admin@koc.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Create regular user
        User::firstOrCreate(
            ['customer_contact' => 'user@koc.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        // Run seeders in order
        $this->call([
            CategorySeeder::class,
            SizeSeeder::class,
            ProductSeeder::class,
            InventorySeeder::class,
        ]);
    }
}
