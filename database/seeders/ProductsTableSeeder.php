<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Product::create([
            'name' => 'Nike ',
            'description' => 'Confort ultime',
            'price' => 120.00,
            'quantity' => 20,
            'sexes' => 'Hommes',
            'image' => 'images/38d0zVgQ4FjEyvszPfCpo3Io82RXrYQyByRxeZmc.webp',
            'is_new' => 0,
            'is_trending' => 0,
            'is_promo' => 1,
            'percent' => 20.00,
            'numberOfStars' => 5
        ]);
    }
}
