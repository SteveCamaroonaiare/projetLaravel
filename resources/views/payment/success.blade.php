@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-sm overflow-hidden">

        {{-- En-tête succès --}}
        <div class="p-6 bg-green-50 border-b border-green-100 flex items-center">
            <div class="bg-green-100 rounded-full p-3 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-green-800">Paiement réussi</h1>
                <p class="text-green-600">Votre commande a été confirmée.</p>
            </div>
        </div>

        {{-- Informations de la commande --}}
        <div class="p-6">
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Détails de la commande</h2>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <x-order.info label="Numéro de commande" :value="$command->id" />
                    <x-order.info label="Date" :value="$command->commandDate->format('d/m/Y H:i')" />
                    <x-order.info label="Statut" :value="$command->status" />
                    <x-order.info label="Méthode de paiement" :value="$command->payments->first()->paiementMethod" />
                </div>
            </div>

            {{-- Liste des articles --}}
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Articles</h2>
                <div class="divide-y">
                    @foreach($command->details as $detail)
                        <div class="py-4 flex gap-4">
                            {{-- Image produit --}}
                            <div class="w-16 h-16 flex-shrink-0">
                                @php $image = $detail->product->images->where('isPrincipal', true)->first(); @endphp
                                @if($image)
                                    <img src="{{ $image->imageUrl }}" alt="{{ $detail->product->name }}"
                                         class="w-full h-full object-cover rounded-md">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center rounded-md">
                                        <span class="text-gray-500 text-xs">Aucune image</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Infos produit --}}
                            <div class="flex-1">
                                <h3 class="font-medium mb-1">{{ $detail->product->name }}</h3>

                                {{-- Variations du produit --}}
                                @if($detail->productVariation)
                                    <div class="text-sm text-gray-600 mb-1">
                                        @foreach($detail->productVariation->variableTypes as $type)
                                            <span>{{ $type->variable->name }}: {{ $type->name }}</span>@if(!$loop->last), @endif
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Quantité + Prix --}}
                                <div class="flex justify-between text-sm">
                                    <span>Quantité : {{ $detail->quantity }}</span>
                                    <span class="font-medium">{{ number_format($detail->unitPrice * $detail->quantity, 2) }} €</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Résumé de paiement --}}
            @php
                $amount = $command->payments->first()->amount;
                $shipping = $amount >= 50 ? 0 : 5.99;
                $total = $amount + $shipping;
            @endphp

            <div class="border-t pt-6">
                <x-order.summary label="Sous-total" :value="number_format($amount, 2) . ' €'" />
                <x-order.summary label="Livraison" :value="$shipping > 0 ? number_format($shipping, 2) . ' €' : 'Gratuit'" />
                <x-order.summary label="Total" :value="number_format($total, 2) . ' €'" bold />
            </div>
        </div>

        {{-- Actions --}}
        <div class="p-6 bg-gray-50 border-t flex justify-between">
            <a href="{{ url('/') }}" class="text-primary hover:underline">Retour à l'accueil</a>
            <button onclick="window.print()" class="text-primary hover:underline">Imprimer la facture</button>
        </div>
    </div>
</div>
@endsection
