@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Catégories</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($categories as $category)
            <a href="{{ route('categories.show', $category->id) }}" class="group block">
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition duration-300 overflow-hidden">
                    <div class="h-48 bg-gray-100">
                        @php
                            $image = $category->images->firstWhere('isPrincipal', true);
                        @endphp

                        @if($image)
                            <img 
                                src="{{ $image->imageUrl }}" 
                                alt="{{ $category->name }}" 
                                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                            >
                        @else
                            <div class="flex items-center justify-center w-full h-full text-gray-400">
                                Aucune image
                            </div>
                        @endif
                    </div>

                    <div class="p-4">
                        <h2 class="text-lg font-semibold mb-1">{{ $category->name }}</h2>
                        <p class="text-gray-600 text-sm line-clamp-2">{{ $category->description }}</p>
                    </div>
                </div>
            </a>
        @empty
            <p class="text-gray-500 col-span-full">Aucune catégorie disponible pour le moment.</p>
        @endforelse
    </div>
</div>
@endsection
