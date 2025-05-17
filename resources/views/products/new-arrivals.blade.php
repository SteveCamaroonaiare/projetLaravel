@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Titre de la page --}}
    <h1 class="text-3xl font-bold mb-6">Nouveautés</h1>

    {{-- Grille des produits --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($newProducts as $product)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">

                {{-- Lien vers la page produit --}}
                <a href="{{ route('products.show', $product->id) }}" class="block">
                    {{-- Image du produit --}}
                    <div class="h-48 overflow-hidden">
                        @php 
                            $mainImage = $product->images->where('isPrincipal', true)->first();
                        @endphp
                        @if($mainImage)
                            <img src="{{ $mainImage->imageUrl }}" alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-500">Aucune image</span>
                            </div>
                        @endif
                    </div>
                </a>

                {{-- Détails produit --}}
                <div class="p-4">
                    {{-- Titre du produit --}}
                    <a href="{{ route('products.show', $product->id) }}" class="block">
                        <h2 class="text-lg font-semibold mb-2 hover:text-primary transition-colors">{{ $product->name }}</h2>
                    </a>

                    {{-- Prix et réduction --}}
                    <div class="flex justify-between items-center mb-3">
                        <div>
                            @if($product->percent > 0)
                                <span class="text-gray-500 line-through mr-2">{{ number_format($product->price, 2) }} €</span>
                                <span class="text-xl font-bold text-primary">
                                    {{ number_format($product->price - ($product->price * $product->percent / 100), 2) }} €
                                </span>
                            @else
                                <span class="text-xl font-bold">{{ number_format($product->price, 2) }} €</span>
                            @endif
                        </div>
                        {{-- Affichage du pourcentage de réduction --}}
                        @if($product->percent > 0)
                            <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs font-bold">
                                -{{ $product->percent }}%
                            </span>
                        @endif
                    </div>

                    {{-- Formulaire ajout au panier --}}
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white py-2 px-4 rounded transition-colors">
                            Ajouter au panier
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $newProducts->links() }}
    </div>
</div>
@endsection
