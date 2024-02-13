<?php

namespace Database\Seeders;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Product::create([
            'id' => 1,
            'product_name' => 'gold_coffee',
        ]);

        Product::create([
            'id' => 2,
            'product_name' => 'arabic_coffee',
        ]);
    }
}
