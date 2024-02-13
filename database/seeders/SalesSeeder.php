<?php

namespace Database\Seeders;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $goldCoffeeId = Product::where('product_name', 'gold_coffee')->value('id');
        $arabicCoffeeId = Product::where('product_name', 'arabic_coffee')->value('id');
        Sale::insert([
            [
                'product_id' => !empty($goldCoffeeId) ? $goldCoffeeId : 1,
                'quantity' => 1,
                'unit_cost' => 10.00,
                'selling_price' => 23.33,
                'sold_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => !empty($arabicCoffeeId) ? $arabicCoffeeId : 2,
                'quantity' => 2,
                'unit_cost' => 20.50,
                'selling_price' => 58.24,
                'sold_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
