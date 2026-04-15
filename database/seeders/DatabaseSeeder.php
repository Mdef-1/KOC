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
        // Create admin user
        User::firstOrCreate(
            ['name' => 'admin'],
            ['password' => Hash::make('password')]
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
