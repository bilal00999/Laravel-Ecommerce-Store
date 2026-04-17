<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the test user or create one
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
                'is_admin' => true,
            ]
        );

        $products = [
            // Laptops
            ['name' => 'MacBook Pro 14"', 'description' => 'Powerful laptop with M2 Pro chip, perfect for professionals', 'price' => 1999.99, 'stock' => 15, 'category_id' => 2],
            ['name' => 'Dell XPS 13', 'description' => 'Ultra-thin and lightweight laptop with Intel Core i7', 'price' => 999.99, 'stock' => 25, 'category_id' => 2],
            ['name' => 'HP Pavilion 15', 'description' => 'Affordable and reliable laptop for everyday use', 'price' => 649.99, 'stock' => 30, 'category_id' => 2],
            ['name' => 'Lenovo ThinkPad X1', 'description' => 'Business laptop with excellent keyboard and security features', 'price' => 1299.99, 'stock' => 20, 'category_id' => 2],
            ['name' => 'ASUS VivoBook 15', 'description' => 'Stylish laptop with vibrant display and long battery life', 'price' => 749.99, 'stock' => 22, 'category_id' => 2],

            // Smartphones
            ['name' => 'iPhone 15 Pro', 'description' => 'Latest Apple smartphone with advanced camera system', 'price' => 999.99, 'stock' => 50, 'category_id' => 3],
            ['name' => 'Samsung Galaxy S24', 'description' => 'Flagship Android phone with stunning display', 'price' => 899.99, 'stock' => 45, 'category_id' => 3],
            ['name' => 'Google Pixel 8', 'description' => 'Pure Android experience with excellent AI features', 'price' => 799.99, 'stock' => 35, 'category_id' => 3],
            ['name' => 'OnePlus 12', 'description' => 'Fast and smooth smartphone with premium build', 'price' => 729.99, 'stock' => 28, 'category_id' => 3],
            ['name' => 'Xiaomi 14', 'description' => 'Feature-rich smartphone at competitive price', 'price' => 599.99, 'stock' => 40, 'category_id' => 3],

            // Tablets
            ['name' => 'iPad Pro 12.9"', 'description' => 'Powerful tablet for creative professionals', 'price' => 1199.99, 'stock' => 18, 'category_id' => 4],
            ['name' => 'Samsung Galaxy Tab S9', 'description' => 'Premium Android tablet with AMOLED display', 'price' => 799.99, 'stock' => 22, 'category_id' => 4],
            ['name' => 'iPad Air', 'description' => 'Balanced tablet for work and entertainment', 'price' => 599.99, 'stock' => 26, 'category_id' => 4],
            ['name' => 'Lenovo Tab P11', 'description' => 'Large screen tablet for multimedia', 'price' => 349.99, 'stock' => 32, 'category_id' => 4],

            // Headphones
            ['name' => 'Sony WH-1000XM5', 'description' => 'Premium wireless headphones with noise cancellation', 'price' => 399.99, 'stock' => 24, 'category_id' => 6],
            ['name' => 'Apple AirPods Pro', 'description' => 'Seamless integration with Apple devices', 'price' => 249.99, 'stock' => 55, 'category_id' => 6],
            ['name' => 'Bose QC45', 'description' => 'Comfortable headphones with excellent sound quality', 'price' => 379.99, 'stock' => 20, 'category_id' => 6],
            ['name' => 'JBL Live Pro 2', 'description' => 'Stylish headphones with great battery life', 'price' => 199.99, 'stock' => 38, 'category_id' => 6],

            // Cameras
            ['name' => 'Canon EOS R5', 'description' => 'Professional mirrorless camera with 45MP sensor', 'price' => 3499.99, 'stock' => 8, 'category_id' => 7],
            ['name' => 'Sony A7 IV', 'description' => 'Full-frame mirrorless camera for photographers', 'price' => 2498.99, 'stock' => 10, 'category_id' => 7],
            ['name' => 'Nikon Z9', 'description' => 'Flagship camera for professional use', 'price' => 5499.99, 'stock' => 5, 'category_id' => 7],
            ['name' => 'GoPro Hero 11', 'description' => 'Action camera for adventure enthusiasts', 'price' => 399.99, 'stock' => 35, 'category_id' => 7],

            // Wearables
            ['name' => 'Apple Watch Series 9', 'description' => 'Feature-rich smartwatch with health tracking', 'price' => 399.99, 'stock' => 40, 'category_id' => 8],
            ['name' => 'Samsung Galaxy Watch 6', 'description' => 'Android-powered smartwatch with beautiful display', 'price' => 299.99, 'stock' => 32, 'category_id' => 8],
            ['name' => 'Garmin Epix', 'description' => 'Advanced sports smartwatch for athletes', 'price' => 699.99, 'stock' => 15, 'category_id' => 8],
            ['name' => 'Fitbit Sense 2', 'description' => 'Health-focused fitness tracker', 'price' => 299.99, 'stock' => 28, 'category_id' => 8],

            // Gaming
            ['name' => 'PlayStation 5', 'description' => 'Next-gen gaming console with 4K gaming', 'price' => 499.99, 'stock' => 12, 'category_id' => 9],
            ['name' => 'Xbox Series X', 'description' => 'Powerful console with Game Pass subscription', 'price' => 499.99, 'stock' => 14, 'category_id' => 9],
            ['name' => 'Nintendo Switch OLED', 'description' => 'Hybrid gaming console with portable play', 'price' => 349.99, 'stock' => 20, 'category_id' => 9],
            ['name' => 'Steam Deck', 'description' => 'Portable PC gaming handheld', 'price' => 449.99, 'stock' => 18, 'category_id' => 9],

            // Smart Home
            ['name' => 'Amazon Echo Show 15', 'description' => 'Smart display for your smart home', 'price' => 249.99, 'stock' => 22, 'category_id' => 10],
            ['name' => 'Google Nest Hub Max', 'description' => 'Video calling and smart home control', 'price' => 229.99, 'stock' => 18, 'category_id' => 10],
            ['name' => 'Philips Hue Lighting Kit', 'description' => 'Smart RGB lighting system', 'price' => 199.99, 'stock' => 26, 'category_id' => 10],
            ['name' => 'LIFX Color A19', 'description' => 'WiFi smart bulb with 16 million colors', 'price' => 89.99, 'stock' => 60, 'category_id' => 10],

            // Accessories
            ['name' => 'USB-C Hub 7-in-1', 'description' => 'Multi-port connectivity hub', 'price' => 49.99, 'stock' => 50, 'category_id' => 5],
            ['name' => 'Portable SSD 1TB', 'description' => 'High-speed external storage', 'price' => 119.99, 'stock' => 40, 'category_id' => 5],
            ['name' => 'Wireless Charger', 'description' => 'Fast charging for your devices', 'price' => 29.99, 'stock' => 70, 'category_id' => 5],
        ];

        foreach ($products as $product) {
            Product::create([
                'name' => $product['name'],
                'slug' => str()->slug($product['name']),
                'description' => $product['description'],
                'price' => $product['price'],
                'stock' => $product['stock'],
                'category_id' => $product['category_id'],
                'user_id' => $user->id,
            ]);
        }
    }
}
