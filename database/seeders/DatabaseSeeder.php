<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        Product::where('image', 'like', '%e1.jpg')->update([
            'color_variants' => json_encode([
                ['name' => 'Rouge', 'code' => '#FF0000', 'suffix' => 'red'],
                ['name' => 'Bleu', 'code' => '#0000FF', 'suffix' => 'blue']
            ])
        ]);
    }
}
