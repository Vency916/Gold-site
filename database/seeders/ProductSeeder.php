<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Product::create([
            'name' => '1oz Gold Britannia Coin',
            'metal' => 'gold',
            'category' => 'coin',
            'weight' => 1.00,
            'weight_unit' => 'oz',
            'purity' => 0.9999,
            'premium_percentage' => 5.00,
            'stock' => 50,
        ]);

        \App\Models\Product::create([
            'name' => '1kg Silver Bar',
            'metal' => 'silver',
            'category' => 'bar',
            'weight' => 32.15, // 1kg in oz
            'weight_unit' => 'oz',
            'purity' => 0.999,
            'premium_percentage' => 15.00,
            'stock' => 10,
        ]);

        \App\Models\Product::create([
            'name' => '1/4oz Gold Sovereign',
            'metal' => 'gold',
            'category' => 'coin',
            'weight' => 0.25,
            'weight_unit' => 'oz',
            'purity' => 0.9167,
            'premium_percentage' => 8.00,
            'stock' => 100,
        ]);
    }
}
