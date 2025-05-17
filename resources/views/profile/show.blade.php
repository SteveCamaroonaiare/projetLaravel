@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Sidebar de navigation du profil -->
        <div class="md:w-1/4">
            @include('profile.partials.sidebar')
        </div>
        
        <!-- Contenu principal -->
        <div class="md:w-3/4">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6 border-b">
                    <h1 class="text-2xl font-bold">Mon profil</h1>
                </div>
                
                <div class="p-6">
                    <div class="flex items-center mb-8">
                        <div class="bg-primary text-white rounded-full w-16 h-16 flex items-center justify-center text-2xl font-bold mr-4">
                            {{ strtoupper(substr($user->firstName, 0, 1) . substr($user->lastName, 0, 1)) }}
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold">{{ $user->firstName }} {{ $user->lastName }}</h2>
                            <p class="text-gray-600">{{ $user->email }}</p>
                        </div>
                    </div>
                    
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold mb-4">Informations personnelles</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-gray-500 mb-1">Prénom</p>
                                <p class="font-medium">{{ $user->firstName }}</p>
                            </div>
                            
                            <div>
                                <p class="text-gray-500 mb-1">Nom</p>
                                <p class="font-medium">{{ $user->lastName }}</p>
                            </div>
                            
                            <div>
                                <p class="text-gray-500 mb-1">Email</p>
                                <p class="font-medium">{{ $user->email }}</p>
                            </div>
                            
                            <div>
                                <p class="text-gray-500 mb-1">Membre depuis</p>
                                <p class="font-medium">{{ $user->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <a href="{{ route('profile.edit') }}" class="inline-flex items-center bg-primary hover:bg-primary-dark text-white py-2 px-4 rounded-md transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                Modifier mes informations
                            </a>
                        </div>
                    </div>
                    
                    <div class="border-t pt-6 mt-6">
                        <h3 class="text-lg font-semibold mb-4">Sécurité</h3>
                        
                        <div>
                            <p class="text-gray-500 mb-1">Mot de passe</p>
                            <p class="font-medium">••••••••</p>
                        </div>
                        
                        <div class="mt-6">
                            <a href="{{ route('profile.edit-password') }}" class="inline-flex items-center bg-primary hover:bg-primary-dark text-white py-2 px-4 rounded-md transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                                Modifier mon mot de passe
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
