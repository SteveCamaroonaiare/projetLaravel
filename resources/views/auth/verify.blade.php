@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <h2 class="text-2xl font-bold mb-6 text-center">Vérification du compte</h2>
            
            <div class="mb-6">
                <p class="text-gray-700 mb-4">
                    Un code de vérification a été envoyé à votre adresse email: <strong>{{ $email }}</strong>
                </p>
                <p class="text-gray-700">
                    Veuillez vérifier votre boîte de réception et entrer le code ci-dessous pour activer votre compte.
                </p>
            </div>
            
            <form method="POST" action="{{ route('verification.verify') }}">
                @csrf
                
                <input type="hidden" name="email" value="{{ $email }}">
                
                <div class="mb-6">
                    <label for="verification_code" class="block text-gray-700 font-medium mb-2">Code de vérification</label>
                    <input type="text" name="verification_code" id="verification_code" required autofocus
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                        placeholder="Entrez le code à 6 chiffres">
                    @error('verification_code')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-3 px-4 rounded-md transition-colors">
                        Vérifier
                    </button>
                </div>
            </form>
            
            <div class="mt-6">
                <p class="text-gray-700 text-center mb-4">
                    Vous n'avez pas reçu le code?
                </p>
                
                <form method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    
                    <button type="submit" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-md transition-colors">
                        Renvoyer le code
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
