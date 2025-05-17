<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Liste des catégories.
     */
    public function index()
    {
        $categories = Category::with('images')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Liste des catégories récupérée.',
            'data' => $categories
        ]);
    }

    /**
     * Créer une nouvelle catégorie.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Catégorie créée avec succès.',
            'data' => $category
        ], 201);
    }

    /**
     * Afficher une catégorie et ses produits.
     */
    public function show($id)
    {
        $category = Category::with('images')->find($id);

        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Catégorie introuvable.'
            ], 404);
        }

        $products = $category->products()->with('images')->paginate(12);

        return response()->json([
            'status' => 'success',
            'message' => 'Produits de la catégorie récupérés.',
            'data' => [
                'category' => $category,
                'products' => $products,
            ]
        ]);
    }

    /**
     * Modifier une catégorie.
     */
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Catégorie non trouvée.'
            ], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Catégorie mise à jour.',
            'data' => $category
        ]);
    }

    /**
     * Supprimer une catégorie.
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Catégorie non trouvée.'
            ], 404);
        }

        $category->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Catégorie supprimée avec succès.'
        ]);
    }
}
