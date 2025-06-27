<?php

// app/Http/Controllers/OrderController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function store(Request $request)
{
    $validated = $request->validate([
    'shipping_address' => 'required|string',
    'payment_method' => 'required|string',
    'email' => 'nullable|email',
    'phone' => 'nullable|string',
    'products' => 'required|array',
    'products.*.product_id' => 'required|exists:products,id',
    'products.*.quantity' => 'required|integer|min:1',
    'products.*.price' => 'required|numeric|min:0',
]);

    // Vérifie s’il y a un utilisateur connecté
    $user = auth('sanctum')->user();

    // Crée la commande même sans utilisateur
    $order = Order::create([
    'user_id' => auth('sanctum')->check() ? auth()->id() : null,
    'shipping_address' => $validated['shipping_address'],
    'payment_method' => $validated['payment_method'],
    'email' => $validated['email'] ?? null,
    'phone' => $validated['phone'] ?? null,
    'status' => 'en attente',
]);

    // Associe les produits
    foreach ($validated['products'] as $product) {
        $order->products()->attach($product['product_id'], [
            'quantity' => $product['quantity'],
            'price' => $product['price'],
        ]);
    }
    

    return response()->json([
        'message' => 'Commande créée avec succès',
        'order' => $order->load('products')
    ], 201);
}

    public function index()
{
    $orders = auth()->user()->orders()->with('products')->get();
    return response()->json($orders);
}
    
}
