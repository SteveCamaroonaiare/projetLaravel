<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;
class ProductController extends Controller
{
    /**
     * Récupérer les nouveautés (produits créés dans les 30 derniers jours).
     */
   // Récupère les nouveautés
    

   // Pour la page HomeShop (produits normaux)
    public function getShopProducts(Request $request)
    {
        $query = Product::query();
        
        // Filtre par catégorie si spécifié
        if ($request->has('category')) {
            $query->where('sexes', $request->category);
        }
        
        $products = $query->get()->map(function($product) {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'image' => asset(str_replace('public/', 'images/', $product->image)),
            'is_promo' => $product->is_promo,
            'percent' => $product->percent,
            'numberOfStars' => $product->numberOfStars
        ];
    });
    
    return response()->json($products);

    }

    // Pour la page Home - Nouveautés
    public function getNewProducts()
    {
        return response()->json(
            Product::where('is_new', true)
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get()
                ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image_url' => asset(str_replace('public/', 'images/', $product->image)),
                    'is_new' => $product->is_new // Pour débogage
                ];
            })
                
        );
    }

    // Pour la page Home - Tendances
    public function getTrendingProducts()
{
    return response()->json(
        Product::where('is_trending', true)
            ->orderBy('created_at', 'desc') // Ou un champ existant comme 'updated_at'
            ->take(8)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image_url' => asset(str_replace('public/', 'images/', $product->image)),
                    'is_trending' => $product->is_trending // Pour débogage
                ];
            })
    );
}

    // Pour la page Home - Promotions
    public function getPromoProducts()
    {
        return response()->json(
            Product::where('is_promo', true)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
                ->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'promo_price' => $product->price * (1 - $product->percent / 100),
                'image_url' => asset(str_replace('public/', 'images/', $product->image)),
                // autres champs...
                'is_promo' => $product->is_promo // Pour débogage

            ];
        })
        );
    }

    // Pour ajouter un produit
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'sexes' => 'required|in:Hommes,Femmes,Enfants',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_new' => 'boolean',
            'is_trending' => 'boolean',
            'is_promo' => 'boolean',
            'percent' => 'required_if:is_promo,true|numeric|min:0|max:100',
            'numberOfStars' => 'required|integer|min:1|max:5',
            'color_variants' => 'nullable|json',
            'moreImgs'=>'nullable|array'


        ]);

            // Conversion des valeurs si nécessaire
        $validated['is_new'] = (bool)($validated['is_new'] ?? false);
        $validated['is_trending'] = (bool)($validated['is_trending'] ?? false);
        $validated['is_promo'] = (bool)($validated['is_promo'] ?? false);


        // Enregistrement de l'image
        $imagePath = $request->file('image')->store('images', 'public');
 $imageUrl = asset(`storage/$imagePath`);
        $product = Product::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'quantity' => $validated['quantity'],
            'sexes' => $validated['sexes'],
            'image' => $imagePath,
            'is_new' => $validated['is_new'] ?? false,
            'is_trending' => $validated['is_trending'] ?? false,
            'is_promo' => $validated['is_promo'] ?? false,
            'percent' => $validated['percent'] ?? 0,
            'numberOfStars' => $request->numberOfStars,
            
            


        ]);

        // Handle additional images
if ($request->hasFile('moreImgs')) {
    foreach ($request->file('moreImgs') as $image) {
        $path = $image->store('images', 'public');
        $product->images()->create(['path' => $path]);
    }
}

        return response()->json(
                $product //->load(['image']) // Charge la relation si nécessaire
            , 201);    }

    
    /*public function uploadImages(Request $request, $id)
        {
            $product = Product::findOrFail($id);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $filename = time() . '_' . $image->getClientOriginalName();
                    $image->storeAs('public/images', $filename);

                    Image::create([
                        'product_id' => $product->id,
                        'filename' => $filename,
                    ]);
                }
            }

            return response()->json(['message' => 'Images uploaded successfully.']);
        }

    /**
     * Récupérer les détails d'un produit avec suggestions.
     */
      // Afficher un produit spécifique
     public function show($id)
    {
        try {
            $product = Product::with('images')->findOrFail($id);
            
            return response()->json($product);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Product not found'
            ], 404);
        }
    }


    
public function index()
    {
        $products = Product::with(['variants.color', 'variants.images'])->get();
        return response()->json($products);
    }



public function homeProducts()
{
    $products = Product::with('images')
        ->select('id', 'name', 'price', 'number_of_stars', 'sex') // Les champs que tu veux
        ->latest()
        ->take(12)
        ->get();

    return response()->json($products);
}



public function filter(Request $request)
{
    $query = Product::query();

    if ($request->has('sexes')) {
        $query->where('sexes', $request->sexes);
    }

    if ($request->has('stars')) {
        $query->where('numberOfStars', '>=', $request->stars);
    }
    
    return response()->json($query->get(), 200);
}


public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);

    $product->update([
        'name' => $request->name,
        'price' => $request->price,
        'description' => $request->description,

        'is_new' => $request->has('is_new') ? 1 : 0,
        'is_trending' => $request->has('is_trending') ? 1 : 0,
        'is_promo' => $request->has('is_promo') ? 1 : 0,
        // autres champs...
    ]);

    return response()->json($product);
}


public function destroy($id)
    {
        Product::destroy($id);
        return response()->json(null, 204);
    }

    

  public function fixImagePaths()
{
    Product::all()->each(function($product) {
        $product->update([
            'image' => str_replace('/storage/public/', '/storage/', $product->image)
        ]);
    });
}

}

