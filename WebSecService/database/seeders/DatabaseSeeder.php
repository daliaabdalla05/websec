<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles first
        $this->call(RoleSeeder::class);

        // Create admin user
        $this->call(AdminSeeder::class);

        // Create categories before products
        $this->call(CategorySeeder::class);

        // Create products
        $this->call(ProductSeeder::class);
        
        // Create a test user with customer role
        $user = User::factory()->create([
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'credit' => 100.00, // Give them some initial credit
        ]);
        
        // Assign the customer role
        $user->assignRole('Customer');
    }
}
