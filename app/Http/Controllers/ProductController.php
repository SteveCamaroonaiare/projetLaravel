<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Image;

class ProductController extends Controller
{
    /**
     * Récupérer les nouveautés (produits créés dans les 30 derniers jours).
     */
   // Récupère les nouveautés
    public function getNewProducts()
    {
        $products = Product::where('is_new', true)->latest()->take(8)->get();
        return response()->json($products, 200);
    }

    // Récupère les tendances
    public function getTrendingProducts()
    {
        $products = Product::where('is_trending', true)->orderBy('updated_at', 'desc')->take(8)->get();
        return response()->json($products, 200);
    }

    // Récupère les promotions
    public function getPromoProducts()
    {
        $products = Product::where('is_promo', true)->orderBy('updated_at', 'desc')->take(8)->get();
        return response()->json($products, 200);
    }




    
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
    public function show($id)
    {
        $product = Product::with(['images', 'category', 'variations.variableTypes.variable'])
            ->findOrFail($id);

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('images')
            ->limit(4)
            ->get();

        return response()->json([
            'product' => $product,
            'related_products' => $relatedProducts
        ]);
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'price' => 'required|numeric',
        'category_id' => 'required|exists:categories,id',
        'numberOfStars' => 'required|integer|min:1|max:5',
        'sexes' => 'required|in:Hommes,Femmes,Enfants',
        'image' => 'required|image|mimes:jpg,jpeg,png'
    ]);

   $product = Product::create([
    'name' => $request->name,
    'description' => $request->description,
    'price' => $request->price,
    'quantity' => $request->quantity,
    'dateOfSale' => $request->dateOfSale,
    'percent' => $request->percent,
    'numberOfSale' => $request->numberOfSale,
    'reference' => $request->reference,
    'category_id' => $request->category_id,
    'numberOfStars' => $request->numberOfStars,
    'sexes' => $request->sexes,
    'is_new' => $request->has('is_new'),
    'is_trending' => $request->has('is_trending'),
    'is_promo' => $request->has('is_promo'),
]);


    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/images', $filename);

        $product->images()->create([
            'image' => $filename,
            'imageUrl' => 'storage/images/' . $filename,
            'isPrincipal' => true
        ]);
    }

    return response()->json($product->load('images'), 201);
}
   

public function index()
{
    $products = Product::all();
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
        'is_new' => $request->has('is_new') ? 1 : 0,
        'is_trending' => $request->has('is_trending') ? 1 : 0,
        'is_promo' => $request->has('is_promo') ? 1 : 0,
        // autres champs...
    ]);

    return response()->json($product);
}


}

