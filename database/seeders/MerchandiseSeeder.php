<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Merchandise;
use Illuminate\Support\Facades\DB;

class MerchandiseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $merchandises = [
            [
                'name' => 'Epic Events T-Shirt',
                'description' => 'Premium cotton t-shirt with Epic Events logo. Available in multiple colors and sizes.',
                'price' => 150000,
                'stock' => 50,
                'category' => 'Clothing',
                'image' => 'tshirt-epic.jpg',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Futuristic Hoodie',
                'description' => 'Navy blue hoodie with futuristic design and Epic Events branding. Perfect for tech events.',
                'price' => 350000,
                'stock' => 30,
                'category' => 'Clothing',
                'image' => 'hoodie-futuristic.jpg',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tech Conference Mug',
                'description' => 'Ceramic mug with heat-sensitive color changing design. Perfect for coffee lovers.',
                'price' => 75000,
                'stock' => 100,
                'category' => 'Accessories',
                'image' => 'mug-tech.jpg',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Event Organizer Cap',
                'description' => 'Adjustable cap with embroidered Epic Events logo. Stylish and comfortable.',
                'price' => 125000,
                'stock' => 75,
                'category' => 'Accessories',
                'image' => 'cap-organizer.jpg',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Premium Notebook',
                'description' => 'High-quality leather notebook with Epic Events branding. Perfect for taking notes during events.',
                'price' => 200000,
                'stock' => 40,
                'category' => 'Stationery',
                'image' => 'notebook-premium.jpg',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'USB Flash Drive 32GB',
                'description' => 'Custom USB flash drive with Epic Events logo. 32GB storage capacity.',
                'price' => 180000,
                'stock' => 60,
                'category' => 'Electronics',
                'image' => 'usb-32gb.jpg',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Wireless Power Bank',
                'description' => 'Portable wireless power bank with Epic Events branding. 10000mAh capacity.',
                'price' => 450000,
                'stock' => 25,
                'category' => 'Electronics',
                'image' => 'powerbank-wireless.jpg',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Event Tote Bag',
                'description' => 'Eco-friendly canvas tote bag with Epic Events design. Perfect for carrying event materials.',
                'price' => 95000,
                'stock' => 80,
                'category' => 'Accessories',
                'image' => 'totebag-event.jpg',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bluetooth Speaker',
                'description' => 'Compact bluetooth speaker with Epic Events logo. Great sound quality for small gatherings.',
                'price' => 650000,
                'stock' => 15,
                'category' => 'Electronics',
                'image' => 'speaker-bluetooth.jpg',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sticker Pack',
                'description' => 'Set of 10 waterproof stickers with various Epic Events designs. Perfect for laptops and phones.',
                'price' => 35000,
                'stock' => 200,
                'category' => 'Accessories',
                'image' => 'stickers-pack.jpg',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($merchandises as $merchandise) {
            Merchandise::create($merchandise);
        }
    }
}