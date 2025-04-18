<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Temporarily disable foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear the existing products table if it exists
        if(Schema::hasTable('products')) {
            DB::table('products')->truncate();
        }

        // Re-enable foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create the products table if it doesn't exist
        if(!Schema::hasTable('products')) {
            Schema::create('products', function ($table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('price', 10, 2);
                $table->integer('inventory')->default(0);
                $table->string('model')->nullable();
                $table->string('code')->nullable();
                $table->string('photo')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Insert all the original product data
        DB::table('products')->insert([
            [
                'id' => 1,
                'code' => 'TV01',
                'name' => 'LG TV 50 Inch',
                'price' => 28000,
                'model' => 'LG8768787',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                'photo' => 'lgtv50.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'inventory' => 10,
            ],
            [
                'id' => 2,
                'code' => 'RF01',
                'name' => 'Toshipa Refrigerator 14"',
                'price' => 22000,
                'model' => 'TS76634',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                'photo' => 'tsrf50.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'inventory' => 5,
            ],
            [
                'id' => 3,
                'code' => 'RF02',
                'name' => 'Toshipa Refrigerator 18"',
                'price' => 28000,
                'model' => 'TS76634',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                'photo' => 'rf2.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'inventory' => 3,
            ],
            [
                'id' => 4,
                'code' => 'RF03',
                'name' => 'Toshipa Refrigerator 19"',
                'price' => 32000,
                'model' => 'TS76634',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                'photo' => 'rf3.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'inventory' => 7,
            ],
            [
                'id' => 5,
                'code' => 'TV02',
                'name' => 'LG TV 55"',
                'price' => 23000,
                'model' => 'LG8768787',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                'photo' => 'tv2.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'inventory' => 4,
            ],
            [
                'id' => 6,
                'code' => 'RF04',
                'name' => 'LG Refrigerator 14"',
                'price' => 22000,
                'model' => 'TS76634',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                'photo' => 'rf4.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'inventory' => 6,
            ],
            [
                'id' => 7,
                'code' => 'TV03',
                'name' => 'LG TV 60"',
                'price' => 44000,
                'model' => 'LG8768787',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                'photo' => 'tv3.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'inventory' => 2,
            ],
            [
                'id' => 8,
                'code' => 'RF05',
                'name' => 'Toshipa Refrigerator 12"',
                'price' => 10700,
                'model' => 'TS76634',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                'photo' => 'rf5.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'inventory' => 8,
            ],
            [
                'id' => 9,
                'code' => 'TV04',
                'name' => 'LG TV 99"',
                'price' => 108000,
                'model' => 'LG8768787',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                'photo' => 'tv4.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'inventory' => 1,
            ],
        ]);

        // Reset auto-increment to the next available ID
        DB::statement('ALTER TABLE products AUTO_INCREMENT = 10');
    }
}
