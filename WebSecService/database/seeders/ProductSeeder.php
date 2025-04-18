<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Smartphone XYZ',
                'description' => 'Latest smartphone with advanced features',
                'price' => 599.99,
                'inventory_count' => 50,
                'is_available' => true,
                'category_id' => 1,
                'image' => 'products/phone.jpg',
                'code' => 'SM001',
                'model' => 'XYZ-2023'
            ],
            [
                'name' => 'Laptop ABC',
                'description' => 'High-performance laptop for professionals',
                'price' => 1299.99,
                'inventory_count' => 30,
                'is_available' => true,
                'category_id' => 1,
                'image' => 'products/laptop.jpg',
                'code' => 'LP001',
                'model' => 'ABC-2023'
            ],
            [
                'name' => 'Wireless Headphones',
                'description' => 'Premium sound quality wireless headphones',
                'price' => 149.99,
                'code' => 'WH003',
                'model' => 'AudioMax',
                'inventory_count' => 100,
                'is_available' => true
            ],
            [
                'name' => 'Smart Watch',
                'description' => 'Track your fitness and stay connected',
                'price' => 249.99,
                'code' => 'SW004',
                'model' => 'FitTrack-Pro',
                'inventory_count' => 75,
                'is_available' => true
            ],
            [
                'name' => 'Tablet Ultra',
                'description' => 'Slim and powerful tablet for work and entertainment',
                'price' => 399.99,
                'code' => 'TU005',
                'model' => 'Ultra-10',
                'inventory_count' => 0,
                'is_available' => false
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
