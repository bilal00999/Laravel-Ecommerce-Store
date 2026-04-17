<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics', 'description' => 'Electronic devices and gadgets'],
            ['name' => 'Laptops', 'description' => 'Computers and laptops'],
            ['name' => 'Smartphones', 'description' => 'Mobile phones and devices'],
            ['name' => 'Tablets', 'description' => 'Tablet computers'],
            ['name' => 'Accessories', 'description' => 'Tech accessories and peripherals'],
            ['name' => 'Headphones', 'description' => 'Audio devices and headphones'],
            ['name' => 'Cameras', 'description' => 'Digital cameras and photography'],
            ['name' => 'Wearables', 'description' => 'Smart watches and wearable tech'],
            ['name' => 'Gaming', 'description' => 'Gaming devices and consoles'],
            ['name' => 'Home & Smart Devices', 'description' => 'Smart home and IoT devices'],
        ];

        foreach ($categories as $category) {
            $category['slug'] = Str::slug($category['name']);
            Category::create($category);
        }
    }
}
