@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Paiement</h1>

    <div class="flex flex-col lg:flex-row gap-8">
        {{-- Formulaire de paiement --}}
        <div class="lg:w-2/3">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-semibold">Informations de paiement</h2>
                </div>

                <div class="p-6">
                    <form action="{{ route('payment.process') }}" method="POST" id="payment-form">
                        @csrf

                        {{-- Numéro de carte --}}
                        <div class="mb-6">
                            <label for="cardNumber" class="block mb-2 font-medium">Numéro de carte</label>
                            <input type="text" id="cardNumber" name="cardNumber" placeholder="1234 5678 9012 3456"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                        </div>

                        {{-- Date d'expiration et CVV --}}
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <label for="expirationDate" class="block mb-2 font-medium">Date d'expiration</label>
                                <input type="text" id="expirationDate" name="expirationDate" placeholder="MM/YYYY"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                            </div>
                            <div>
                                <label for="cvv" class="block mb-2 font-medium">CVV</label>
                                <input type="text" id="cvv" name="cvv" placeholder="123"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                            </div>
                        </div>

                        {{-- Devise --}}
                        <div class="mb-6">
                            <label for="currency_id" class="block mb-2 font-medium">Devise</label>
                            <select id="currency_id" name="currency_id"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->id }}">{{ $currency->name }} ({{ $currency->code }})</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Bouton paiement --}}
                        <button type="submit"
                            class="w-full bg-primary hover:bg-primary-dark text-white py-3 px-4 rounded-lg font-medium transition-colors">
                            Payer {{ number_format($total, 2) }} €
                        </button>
                    </form>
                </div>
            </div>

            {{-- Détail des produits --}}
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-semibold">Récapitulatif de la commande</h2>
                </div>

                <div class="divide-y">
                    @foreach($cart->products as $product)
                        <div class="p-6 flex gap-4">
                            {{-- Image produit --}}
                            <div class="w-16 h-16 flex-shrink-0">
                                @php
                                    $principalImage = $product->images->where('isPrincipal', true)->first();
                                @endphp
                                @if($principalImage)
                                    <img src="{{ $principalImage->imageUrl }}" alt="{{ $product->name }}"
                                        class="w-full h-full object-cover rounded-md">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center rounded-md">
                                        <span class="text-gray-500">Aucune image</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Infos produit --}}
                            <div class="flex-1">
                                <h3 class="font-medium mb-1">{{ $product->name }}</h3>

                                {{-- Variations --}}
                                @if($product->pivot->product_variation_id)
                                    @php
                                        $variation = \App\Models\ProductVariation::with('variableTypes.variable')->find($product->pivot->product_variation_id);
                                    @endphp
                                    @if($variation)
                                        <div class="text-sm text-gray-600 mb-1">
                                            @foreach($variation->variableTypes as $type)
                                                <span>{{ $type->variable->name }}: {{ $type->name }}@if(!$loop->last), @endif</span>
                                            @endforeach
                                        </div>
                                    @endif
                                @endif

                                {{-- Quantité + Prix total --}}
                                <div class="flex justify-between text-sm mt-1">
                                    <div>Quantité: {{ $product->pivot->quantity }}</div>
                                    <div class="font-medium">
                                        @php
                                            $price = $product->price;
                                            if($product->percent > 0) {
                                                $price -= ($price * $product->percent / 100);
                                            }
                                            $itemTotal = $price * $product->pivot->quantity;
                                        @endphp
                                        {{ number_format($itemTotal, 2) }} €
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Récapitulatif total --}}
        <div class="lg:w-1/3">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden sticky top-4">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-semibold">Récapitulatif</h2>
                </div>

                <div class="p-6 space-y-4">
                    <div class="flex justify-between">
                        <span>Sous-total</span>
                        <span>{{ number_format($total, 2) }} €</span>
                    </div>

                    <div class="flex justify-between">
                        <span>Livraison</span>
                        @php $shipping = $total >= 50 ? 0 : 5.99; @endphp
                        <span>{{ $shipping > 0 ? number_format($shipping, 2).' €' : 'Gratuit' }}</span>
                    </div>

                    <div class="border-t pt-4 mt-4">
                        <div class="flex justify-between font-bold text-lg">
                            <span>Total</span>
                            <span>{{ number_format($total + $shipping, 2) }} €</span>
                        </div>
                        <div class="text-sm text-gray-500 mt-1">TVA incluse</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Scripts JS pour le formatage des champs --}}
<script>
    document.getElementById('cardNumber').addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '').slice(0, 16);
        e.target.value = value;
    });

    document.getElementById('expirationDate').addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 2) {
            let month = Math.min(parseInt(value.slice(0, 2)), 12).toString().padStart(2, '0');
            let year = value.slice(2, 6);
            e.target.value = month + (year ? '/' + year : '');
        } else {
            e.target.value = value;
        }
    });

    document.getElementById('cvv').addEventListener('input', function (e) {
        e.target.value = e.target.value.replace(/\D/g, '').slice(0, 4);
    });
</script>
@endsection
