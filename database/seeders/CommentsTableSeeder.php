<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Comment::create([
            'message' => 'Super produit !',
            'creationDate' => now(),
            'numberOfStars' => 5,
            'product_id' => 1,
            'user_id' => 1
        ]);
    }
}
