@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Retour au produit --}}
    <div class="mb-6">
        <a href="{{ route('products.show', $product->id) }}" class="text-primary hover:underline flex items-center">
            <x-icon.back class="w-5 h-5 mr-1" />
            Retour au produit
        </a>
    </div>

    <div class="flex flex-col md:flex-row gap-8">
        {{-- Détails du produit --}}
        <aside class="md:w-1/3 lg:w-1/4">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <img 
                    src="{{ $product->images->where('isPrincipal', true)->first()->imageUrl ?? '/placeholder.jpg' }}" 
                    alt="{{ $product->name }}" 
                    class="w-full h-auto object-cover rounded-md p-4"
                >

                <div class="p-4 border-t">
                    <h2 class="font-semibold text-lg mb-2">{{ $product->name }}</h2>

                    {{-- Note moyenne --}}
                    <div class="flex items-center mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <x-icon.star :filled="$i <= $product->comments->avg('numberOfStars')" />
                        @endfor
                        <span class="ml-2 text-gray-600">({{ $product->comments->count() }} avis)</span>
                    </div>

                    {{-- Prix --}}
                    <div class="mb-4">
                        @if($product->percent > 0)
                            <div class="flex items-center mb-1">
                                <span class="text-gray-500 line-through mr-2">{{ number_format($product->price, 2) }} €</span>
                                <span class="text-xl font-bold text-primary">
                                    {{ number_format($product->price * (1 - $product->percent / 100), 2) }} €
                                </span>
                            </div>
                            <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs font-bold">-{{ $product->percent }}%</span>
                        @else
                            <span class="text-xl font-bold">{{ number_format($product->price, 2) }} €</span>
                        @endif
                    </div>

                    <a href="{{ route('products.show', $product->id) }}" class="block bg-primary hover:bg-primary-dark text-white text-center py-2 rounded-lg transition-colors">
                        Voir le produit
                    </a>
                </div>
            </div>
        </aside>

        {{-- Liste des avis --}}
        <section class="md:w-2/3 lg:w-3/4">
            <h1 class="text-2xl font-bold mb-6">Avis clients ({{ $comments->total() }})</h1>

            @if($comments->count())
                <div class="space-y-6 mb-8">
                    @foreach($comments as $comment)
                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <div class="font-medium">{{ $comment->user->firstName }} {{ $comment->user->lastName }}</div>
                                    <div class="text-gray-500 text-sm">{{ $comment->creationDate->format('d/m/Y') }}</div>
                                </div>
                                <div class="flex">
                                    @for($i = 1; $i <= 5; $i++)
                                        <x-icon.star :filled="$i <= $comment->numberOfStars" />
                                    @endfor
                                </div>
                            </div>
                            <p class="text-gray-700">{{ $comment->message }}</p>
                        </div>
                    @endforeach
                </div>
                {{ $comments->links() }}
            @else
                <div class="bg-gray-50 p-6 rounded-lg text-center text-gray-600">
                    Aucun avis pour ce produit.
                </div>
            @endif

            {{-- Ajouter un commentaire --}}
            @auth
                <div id="add-comment" class="mt-8 bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-xl font-semibold mb-4">Ajouter un avis</h3>

                    <form action="{{ route('comments.store', $product->id) }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="rating" class="block mb-2 font-medium">Note</label>
                            <div class="flex">
                                @for($i = 1; $i <= 5; $i++)
                                    <input type="radio" id="star{{ $i }}" name="numberOfStars" value="{{ $i }}" class="hidden">
                                    <label for="star{{ $i }}" class="cursor-pointer p-1">
                                        <x-icon.star :filled="false" class="w-8 h-8 hover:text-yellow-400" />
                                    </label>
                                @endfor
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="message" class="block mb-2 font-medium">Votre avis</label>
                            <textarea name="message" rows="4" required class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary"></textarea>
                        </div>

                        <button type="submit" class="bg-primary hover:bg-primary-dark text-white py-2 px-4 rounded-md transition-colors">
                            Publier mon avis
                        </button>
                    </form>
                </div>
            @else
                <div class="mt-8 bg-gray-50 p-6 rounded-lg text-center text-gray-600">
                    <a href="{{ route('login') }}" class="text-primary hover:underline">Connectez-vous</a> pour laisser un avis.
                </div>
            @endauth
        </section>
    </div>
</div>
@endsection
