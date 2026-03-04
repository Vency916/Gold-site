<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DigitalProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Product::updateOrCreate(
            ['name' => 'Institutional Digital Gold'],
            [
                'metal' => 'gold',
                'category' => 'bar',
                'weight' => 1.00,
                'weight_unit' => 'g',
                'purity' => 0.9999,
                'premium_percentage' => 0.00,
                'premium_fixed' => 0.00,
                'stock' => 1000000,
            ]
        );

        \App\Models\Product::updateOrCreate(
            ['name' => 'Institutional Digital Silver'],
            [
                'metal' => 'silver',
                'category' => 'bar',
                'weight' => 1.00,
                'weight_unit' => 'g',
                'purity' => 0.9990,
                'premium_percentage' => 0.00,
                'premium_fixed' => 0.00,
                'stock' => 5000000,
            ]
        );
    }
}
