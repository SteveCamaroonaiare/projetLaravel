@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Images du produit -->
        <div class="lg:w-1/2">
            <div class="mb-4">
                @php
                    $mainImage = $product->images->where('isPrincipal', true)->first();
                @endphp
                @if($mainImage)
                    <img id="main-image" src="{{ $mainImage->imageUrl }}" alt="{{ $product->name }}" class="w-full h-96 object-cover rounded-lg">
                @else
                    <div class="w-full h-96 bg-gray-200 flex items-center justify-center rounded-lg">
                        <span class="text-gray-500">Aucune image</span>
                    </div>
                @endif
            </div>

            @if($product->images->count() > 1)
                <div class="grid grid-cols-5 gap-2">
                    @foreach($product->images as $image)
                        <img src="{{ $image->imageUrl }}" alt="{{ $product->name }}" class="w-full h-24 object-cover rounded-lg cursor-pointer hover:opacity-80 transition-opacity"
                            onclick="document.getElementById('main-image').src = '{{ $image->imageUrl }}'">
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Détails du produit -->
        <div class="lg:w-1/2">
            <h1 class="text-3xl font-bold mb-4">{{ $product->name }}</h1>
            <!-- Évaluation du produit -->
            <div class="mb-4">
                <div class="flex items-center mb-2">
                    @foreach(range(1, 5) as $i)
                        <svg class="w-5 h-5 {{ $i <= $product->comments->avg('numberOfStars') ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    @endforeach
                    <span class="ml-2 text-gray-600">({{ $product->comments->count() }} avis)</span>
                </div>
            </div>

            <!-- Prix et description -->
            <div class="mb-6">
                <span class="text-gray-500 line-through mr-3 text-xl">{{ number_format($product->price, 2) }} €</span>
                @if($product->percent > 0)
                    <span class="text-3xl font-bold text-primary">
                        {{ number_format($product->price - ($product->price * $product->percent / 100), 2) }} €
                    </span>
                    <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs font-bold">-{{ $product->percent }}%</span>
                @else
                    <span class="text-3xl font-bold">{{ number_format($product->price, 2) }} €</span>
                @endif
            </div>
            <p class="text-gray-700">{{ $product->description }}</p>

            <!-- Variantes disponibles -->
            @if($product->variations->count() > 0)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-3">Variantes disponibles</h3>
                    <div class="grid grid-cols-2 gap-4">
                        @foreach($product->variations as $variation)
                            <div class="border rounded-lg p-3 {{ $variation->isAvailable ? 'cursor-pointer hover:border-primary' : 'opacity-50 cursor-not-allowed' }}">
                                @foreach($variation->variableTypes as $type)
                                    <span class="text-sm"><strong>{{ $type->variable->name }}:</strong> {{ $type->name }}</span>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Formulaire d'ajout au panier -->
            <form action="{{ route('cart.add') }}" method="POST" class="mb-6">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="flex items-center mb-4">
                    <label for="quantity" class="mr-3 font-medium">Quantité:</label>
                    <div class="flex items-center border rounded-md">
                        <button type="button" class="px-3 py-1 bg-gray-100 hover:bg-gray-200" onclick="decrementQuantity()">-</button>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->quantity }}" class="w-16 text-center border-none focus:ring-0">
                        <button type="button" class="px-3 py-1 bg-gray-100 hover:bg-gray-200" onclick="incrementQuantity()">+</button>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-primary hover:bg-primary-dark text-white py-3 px-6 rounded-lg font-medium transition-colors">
                        Ajouter au panier
                    </button>
                    <button type="button" class="bg-gray-100 hover:bg-gray-200 p-3 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Commentaires -->
    <div class="mt-12">
        <h2 class="text-2xl font-bold mb-6">Avis clients</h2>
        <div class="mb-8">
            <a href="{{ route('comments.index', $product->id) }}" class="text-primary hover:underline">Voir tous les avis ({{ $product->comments->count() }})</a>
        </div>
        @if($product->comments->count() > 0)
            <div class="grid gap-6">
                @foreach($product->comments->take(3) as $comment)
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <div class="font-medium">{{ $comment->user->firstName }} {{ $comment->user->lastName }}</div>
                                <div class="text-gray-500 text-sm">{{ $comment->creationDate->format('d/m/Y') }}</div>
                            </div>
                            <div class="flex">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= $comment->numberOfStars ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-
