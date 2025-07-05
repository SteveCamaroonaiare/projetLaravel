<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    // database/seeders/DatabaseSeeder.php
// database/seeders/DatabaseSeeder.php

public function run()
{
    $this->call([
        UsersTableSeeder::class,
        CategoriesTableSeeder::class,
        ProductsTableSeeder::class,
        ProductVariantsTableSeeder::class,
        ImagesTableSeeder::class,
        CommentsTableSeeder::class,
        CartsTableSeeder::class,
    ]);
    // ... (création des tailles, couleurs et produits comme précédemment)

    // Chemin absolu vers le dossier public/images
    $imagesPath = public_path('images');
    
    // Liste des images disponibles avec vérification d'existence
    $availableImages = collect([
        'a1.jpg',
        'aa.png',
        'as.jpeg',
        'avatar.jpg'
        // Ajoutez toutes vos images potentielles ici
    ])->filter(function ($image) use ($imagesPath) {
        return File::exists($imagesPath.'/'.$image);
    })->toArray();

    // Fallback si aucune image n'est trouvée
    if (empty($availableImages)) {
        throw new \Exception("Aucune image valide trouvée dans public/images/");
    }

    // Création des variantes avec images vérifiées
    foreach (\App\Models\Color::all() as $index => $color) {
        $imageName = $availableImages[$index % count($availableImages)];
        
        $variant = $product->variants()->create(['color_id' => $color->id]);
        $variant->sizes()->attach($sizes->random(3));
        
        $variant->images()->create([
            'path' => 'images/'.$imageName, // Chemin relatif
            'is_main' => true
        ]);
    }
}
}
