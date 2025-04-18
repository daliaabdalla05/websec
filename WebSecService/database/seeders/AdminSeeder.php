<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if it doesn't exist
        $admin = User::where('email', 'admin@example.com')->first();
        
        if (!$admin) {
            $admin = User::create([
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => Hash::make('Admin123!'),
                'credit' => 0
            ]);
            
            // Get the admin role
            $adminRole = Role::findByName('Admin', 'web');
            
            // Assign the admin role to the user
            if ($adminRole) {
                $admin->assignRole($adminRole);
            }
        }
    }
}
