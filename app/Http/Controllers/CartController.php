<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getCart()->load('products');
        
        return response()->json([
            'status' => 'success',
            'message' => 'Contenu du panier récupéré avec succès.',
            'data' => $cart,
        ]);
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'product_variation_id' => 'nullable|exists:product_variations,id',
        ]);

        $cart = $this->getCart();

        $existingProduct = $cart->products()
            ->wherePivot('product_variation_id', $request->product_variation_id)
            ->wherePivot('product_id', $request->product_id)
            ->first();

        if ($existingProduct) {
            $newQuantity = $existingProduct->pivot->quantity + $request->quantity;
            $cart->products()->updateExistingPivot($request->product_id, [
                'quantity' => $newQuantity,
                'product_variation_id' => $request->product_variation_id,
            ]);
        } else {
            $cart->products()->attach($request->product_id, [
                'quantity' => $request->quantity,
                'product_variation_id' => $request->product_variation_id,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Produit ajouté au panier avec succès.',
        ]);
    }

    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variation_id' => 'nullable|exists:product_variations,id',
        ]);

        $cart = $this->getCart();

        $cart->products()
            ->wherePivot('product_id', $request->product_id)
            ->wherePivot('product_variation_id', $request->product_variation_id)
            ->detach();

        return response()->json([
            'status' => 'success',
            'message' => 'Produit supprimé du panier avec succès.',
        ]);
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'product_variation_id' => 'nullable|exists:product_variations,id',
        ]);

        $cart = $this->getCart();

        $cart->products()
            ->wherePivot('product_variation_id', $request->product_variation_id)
            ->updateExistingPivot($request->product_id, [
                'quantity' => $request->quantity,
            ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Panier mis à jour avec succès.',
        ]);
    }

    public function clearCart()
    {
        $cart = $this->getCart();
        $cart->products()->detach();

        return response()->json([
            'status' => 'success',
            'message' => 'Panier vidé avec succès.',
        ]);
    }

    private function getCart()
    {
        if (auth()->check()) {
            return Cart::firstOrCreate(['user_id' => auth()->id()]);
        } else {
            $cartId = session('cart_id');
            if ($cartId) {
                $cart = Cart::find($cartId);
                if (!$cart) {
                    $cart = Cart::create();
                    session(['cart_id' => $cart->id]);
                }
            } else {
                $cart = Cart::create();
                session(['cart_id' => $cart->id]);
            }
            return $cart;
        }
    }
}
