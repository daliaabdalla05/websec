<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $customerRole = Role::firstOrCreate(['name' => 'Customer', 'guard_name' => 'web']);
        $employeeRole = Role::firstOrCreate(['name' => 'Employee', 'guard_name' => 'web']);
        
        // Create permissions
        $manageProducts = Permission::firstOrCreate(['name' => 'manage_products', 'guard_name' => 'web']);
        $manageCustomers = Permission::firstOrCreate(['name' => 'manage_customers', 'guard_name' => 'web']);
        $manageEmployees = Permission::firstOrCreate(['name' => 'manage_employees', 'guard_name' => 'web']);
        $purchaseProducts = Permission::firstOrCreate(['name' => 'purchase_products', 'guard_name' => 'web']);
        
        // Assign permissions to roles
        $adminRole->givePermissionTo([
            $manageProducts,
            $manageCustomers,
            $manageEmployees,
            $purchaseProducts
        ]);
        
        $employeeRole->givePermissionTo([
            $manageProducts,
            $manageCustomers
        ]);
        
        $customerRole->givePermissionTo([
            $purchaseProducts
        ]);
    }
}
