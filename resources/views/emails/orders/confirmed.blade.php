@component('mail::message')
# Bonjour {{ $order->user ? $order->user->name : 'Client' }},

Merci pour votre commande !

**Détails de la commande :**

@foreach ($order->products as $product)
- {{ $product->name }} x {{ $product->pivot->quantity }} : {{ $product->pivot->price }} MAD
@endforeach

**Total :** {{ $order->products->sum(fn($p) => $p->pivot->price * $p->pivot->quantity) }} MAD

Nous vous tiendrons informé de l'état de votre commande.

Merci de votre confiance,  
{{ config('app.name') }}
<x-mail::message>
# Introduction

The body of your message.

<x-mail::button :url="''">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}

</x-mail::message>
@endcomponent
