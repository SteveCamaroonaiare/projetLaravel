<?php
namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CommentController extends Controller
{
    /**
     * Récupérer les commentaires d'un produit.
     */
    public function index($productId)
    {
        $product = Product::findOrFail($productId);
        $comments = $product->comments()
            ->with('user')
            ->orderBy('creationDate', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'comments' => $comments
        ]);
    }

    /**
     * Ajouter un commentaire à un produit.
     */
    public function store(Request $request, $productId)
    {
        $request->validate([
            'message' => 'required|string',
            'numberOfStars' => 'required|integer|min:1|max:5',
        ]);

        $product = Product::findOrFail($productId);
        
        $comment = new Comment([
            'message' => $request->message,
            'numberOfStars' => $request->numberOfStars,
            'creationDate' => Carbon::now(),
            'user_id' => auth()->id(),
        ]);
        
        $product->comments()->save($comment);
        
        // Récupérer le commentaire avec les informations de l'utilisateur
        $comment->load('user');
        
        return response()->json([
            'success' => true,
            'message' => 'Commentaire ajouté avec succès.',
            'comment' => $comment
        ]);
    }
    
    /**
     * Supprimer un commentaire.
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        
        // Vérifier que l'utilisateur est autorisé à supprimer ce commentaire
        if (auth()->id() !== $comment->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé à supprimer ce commentaire.'
            ], 403);
        }
        
        $comment->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Commentaire supprimé avec succès.'
        ]);
    }
}