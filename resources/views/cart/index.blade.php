@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Votre panier</h1>

    @if($cart && $cart->products->count())
        <div class="flex flex-col lg:flex-row gap-8">
            {{-- Liste des articles --}}
            <div class="lg:w-2/3 space-y-4">
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-6 border-b">
                        <h2 class="text-xl font-semibold">Articles ({{ $cart->products->count() }})</h2>
                    </div>

                    <div class="divide-y">
                        @foreach($cart->products as $product)
                            @php
                                $principalImage = $product->images->where('isPrincipal', true)->first();
                                $variation = \App\Models\ProductVariation::with('variableTypes.variable')
                                    ->find($product->pivot->product_variation_id);
                                $finalPrice = $product->percent > 0 
                                    ? $product->price * (1 - $product->percent / 100)
                                    : $product->price;
                            @endphp

                            <div class="p-6 flex flex-col sm:flex-row gap-4">
                                {{-- Image --}}
                                <div class="sm:w-24 h-24 flex-shrink-0">
                                    @if($principalImage)
                                        <img src="{{ $principalImage->imageUrl }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-md">
                                    @else
                                        <div class="w-full h-full bg-gray-200 flex items-center justify-center rounded-md">
                                            <span class="text-gray-500">Aucune image</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Détails produit --}}
                                <div class="flex-1 flex flex-col sm:flex-row sm:justify-between">
                                    <div>
                                        <h3 class="font-medium text-lg mb-1">
                                            <a href="{{ route('products.show', $product->id) }}" class="hover:text-primary">
                                                {{ $product->name }}
                                            </a>
                                        </h3>

                                        {{-- Variantes --}}
                                        @if($variation)
                                            <div class="text-sm text-gray-600 mb-2">
                                                @foreach($variation->variableTypes as $type)
                                                    <span>{{ $type->variable->name }}: {{ $type->name }}@if(!$loop->last), @endif</span>
                                                @endforeach
                                            </div>
                                        @endif

                                        {{-- Prix --}}
                                        <div class="text-primary font-medium">
                                            @if($product->percent > 0)
                                                <span class="line-through text-gray-500 mr-2">{{ number_format($product->price, 2) }} €</span>
                                            @endif
                                            {{ number_format($finalPrice, 2) }} €
                                        </div>
                                    </div>

                                    {{-- Quantité et suppression --}}
                                    <div class="mt-4 sm:mt-0 flex items-center">
                                        <form action="{{ route('cart.update') }}" method="POST" class="flex items-center">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="product_variation_id" value="{{ $product->pivot->product_variation_id }}">

                                            <div class="flex items-center border rounded-md mr-4">
                                                <button type="button" class="px-3 py-1 bg-gray-100 hover:bg-gray-200"
                                                    onclick="this.parentNode.querySelector('input').stepDown(); this.form.submit();">-</button>
                                                <input type="number" name="quantity" value="{{ $product->pivot->quantity }}" min="1" max="{{ $product->quantity }}" class="w-12 text-center border-none focus:ring-0">
                                                <button type="button" class="px-3 py-1 bg-gray-100 hover:bg-gray-200"
                                                    onclick="this.parentNode.querySelector('input').stepUp(); this.form.submit();">+</button>
                                            </div>
                                        </form>

                                        <form action="{{ route('cart.remove') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="product_variation_id" value="{{ $product->pivot->product_variation_id }}">
                                            <button type="submit" class="text-gray-500 hover:text-red-500">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex justify-between">
                    <a href="{{ url('/') }}" class="text-primary hover:underline flex items-center">
                        ← Continuer mes achats
                    </a>
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        <button class="text-red-500 hover:underline">Vider le panier</button>
                    </form>
                </div>
            </div>

            {{-- Récapitulatif --}}
            <div class="lg:w-1/3">
                @php
                    $subtotal = $cart->products->sum(function ($product) {
                        $price = $product->price;
                        if ($product->percent > 0) {
                            $price -= $price * $product->percent / 100;
                        }
                        return $price * $product->pivot->quantity;
                    });
                    $shipping = $subtotal >= 50 ? 0 : 5.99;
                @endphp

                <div class="bg-white rounded-lg shadow-sm sticky top-4">
                    <div class="p-6 border-b">
                        <h2 class="text-xl font-semibold">Récapitulatif</h2>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="flex justify-between">
                            <span>Sous-total</span>
                            <span>{{ number_format($subtotal, 2) }} €</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Livraison</span>
                            <span>{{ $shipping > 0 ? number_format($shipping, 2) . ' €' : 'Gratuit' }}</span>
                        </div>

                        <div class="border-t pt-4 mt-4">
                            <div class="flex justify-between font-bold text-lg">
                                <span>Total</span>
                                <span>{{ number_format($subtotal + $shipping, 2) }} €</span>
                            </div>
                            <div class="text-sm text-gray-500 mt-1">TVA incluse</div>
                        </div>

                        <a href="{{ route('payment.checkout') }}" class="block bg-primary hover:bg-primary-dark text-white text-center py-3 rounded-lg font-medium mt-6">
                            Procéder au paiement
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Panier vide --}}
        <div class="bg-white rounded-lg shadow-sm p-8 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <h2 class="text-2xl font-semibold mb-2">Votre panier est vide</h2>
            <p class="mb-6">Découvrez nos produits et ajoutez-les à votre panier.</p>
            <a href="{{ url('/') }}" class="bg-primary hover:bg-primary-dark text-white py-3 px-6 rounded-lg font-medium">
                Commencer mes achats
            </a>
        </div>
    @endif
</div>
@endsection
