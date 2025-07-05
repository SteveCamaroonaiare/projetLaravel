<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Image::create([
            ['product_id' => 1,
            'path' => 'images/e1.jpg',
            'is_main' => 1],
            ['product_id' => 2,
            'path' => 'images/e2.jpg',
            'is_main' => 1],
            ['product_id' => 3,
            'path' => 'images/a1.jpg',
            'is_main' => 1],
            ['product_id' => 4,
            'path' => 'images/n5FPOIiraGon5cDFSLLnKeHr6v4RWSdI3GEnGmle.jpg',
            'is_main' => 0],
            ['product_id' => 5,
            'path' => 'images/09pH3HudDpVp4hxjxDo6T7UtJYjd8LFJgtN8IaDa.jpg',
            'is_main' => 0],
            ['product_id' => 6,
            'path' => 'images/S5Bo5ACX3Ljf2Wkluo2PAM4QShE20a48jTWQKowW.jpg',
            'is_main' => 0],
            ['product_id' => 7,
            'path' => 'images/C6E0539OYUrc6jEhDojTC8DLFaKDOQ5sqnPcsCdZ.webp',
            'is_main' => 0],
            ['product_id' => 8,
            'path' => 'images/C6E0539OYUrc6jEhDojTC8DLFaKDOQ5sqnPcsCdZ.webp',  
            'is_main' => 0],
            ['product_id' => 9,
            'path' => 'images/38d0zVgQ4FjEyvszPfCpo3Io82RXrYQyByRxeZmc.webp',
            'is_main' => 0],
        ]);
    }
}
