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
                <div class="p-6 border-b flex justify-between items-center">
                    <h1 class="text-2xl font-bold">Détails de la commande #{{ $order->id }}</h1>
                    <a href="{{ route('profile.orders') }}" class="inline-flex items-center text-gray-700 hover:text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Retour aux commandes
                    </a>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h2 class="text-lg font-semibold mb-3">Informations de commande</h2>
                            <div class="bg-gray-50 p-4 rounded-md">
                                <div class="mb-2">
                                    <span class="text-gray-500">Numéro de commande:</span>
                                    <span class="font-medium ml-2">#{{ $order->id }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="text-gray-500">Date:</span>
                                    <span class="font-medium ml-2">{{ $order->commandDate->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="text-gray-500">Statut:</span>
                                    <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($order->status == 'En attente') bg-yellow-100 text-yellow-800
                                        @elseif($order->status == 'Payé') bg-green-100 text-green-800
                                        @elseif($order->status == 'Expédié') bg-blue-100 text-blue-800
                                        @elseif($order->status == 'Livré') bg-purple-100 text-purple-800
                                        @elseif($order->status == 'Annulé') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                    {{ $order->status }}
                                </span>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h2 class="text-lg font-semibold mb-3">Informations de paiement</h2>
                            <div class="bg-gray-50 p-4 rounded-md">
                                @if($order->payments->count() > 0)
                                    @php $payment = $order->payments->first(); @endphp
                                    <div class="mb-2">
                                        <span class="text-gray-500">Méthode:</span>
                                        <span class="font-medium ml-2">{{ $payment->paiementMethod }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <span class="text-gray-500">Date:</span>
                                        <span class="font-medium ml-2">{{ $payment->paiementDate->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <span class="text-gray-500">Statut:</span>
                                        <span class="font-medium ml-2">{{ $payment->status }}</span>
                                    </div>
                                @else
                                    <p class="text-gray-500">Aucune information de paiement disponible.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <h2 class="text-lg font-semibold mb-3">Articles commandés</h2>
                    <div class="overflow-x-auto mb-8">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Produit
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Prix unitaire
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Quantité
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($order->details as $detail)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    @if($detail->product && $detail->product->images->where('isPrincipal', true)->first())
                                                        <img class="h-10 w-10 rounded-md object-cover" src="{{ $detail->product->images->where('isPrincipal', true)->first()->imageUrl }}" alt="{{ $detail->product->name }}">
                                                    @else
                                                        <div class="h-10 w-10 rounded-md bg-gray-200 flex items-center justify-center">
                                                            <span class="text-gray-500 text-xs">No img</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $detail->product ? $detail->product->name : 'Produit indisponible' }}
                                                    </div>
                                                    @if($detail->productVariation)
                                                        <div class="text-sm text-gray-500">
                                                            @foreach($detail->productVariation->variableTypes as $type)
                                                                <span>{{ $type->variable->name }}: {{ $type->name }}</span>
                                                                @if(!$loop->last), @endif
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ number_format($detail->unitPrice, 2) }} €</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $detail->quantity }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ number_format($detail->unitPrice * $detail->quantity, 2) }} €</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="border-t pt-6">
                        <div class="flex justify-end">
                            <div class="w-full md:w-1/3">
                                <div class="flex justify-between mb-2">
                                    <span>Sous-total</span>
                                    <span>
                                        @php
                                            $subtotal = 0;
                                            foreach($order->details as $detail) {
                                                $subtotal += $detail->unitPrice * $detail->quantity;
                                            }
                                        @endphp
                                        {{ number_format($subtotal, 2) }} €
                                    </span>
                                </div>
                                
                                <div class="flex justify-between mb-2">
                                    <span>Livraison</span>
                                    <span>
                                        @php
                                            $shipping = $subtotal >= 50 ? 0 : 5.99;
                                        @endphp
                                        {{ $shipping > 0 ? number_format($shipping, 2) . ' €' : 'Gratuit' }}
                                    </span>
                                </div>
                                
                                <div class="flex justify-between font-bold text-lg border-t pt-2 mt-2">
                                    <span>Total</span>
                                    <span>{{ number_format($subtotal + $shipping, 2) }} €</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
