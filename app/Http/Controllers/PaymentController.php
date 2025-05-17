<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Command;
use App\Models\CommandDetail;
use App\Models\Paiement;
use App\Models\CardInfo;
use App\Models\Currency;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Afficher le formulaire de paiement.
     * Renvoie les informations nécessaires pour afficher le formulaire de paiement.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkout()
    {
        // Vérifier si l'utilisateur est connecté
        if (!auth()->check()) {
            return response()->json(['error' => 'Veuillez vous connecter pour finaliser votre commande.'], 401);
        }
        
        $cart = Cart::where('user_id', auth()->id())->first();
        
        if (!$cart || $cart->products->isEmpty()) {
            return response()->json(['error' => 'Votre panier est vide.'], 400);
        }
        
        // Calculer le total
        $total = 0;
        foreach ($cart->products as $product) {
            $price = $product->price;
            
            if ($product->percent > 0) {
                $price = $price - ($price * $product->percent / 100);
            }
            
            $total += $price * $product->pivot->quantity;
        }
        
        // Récupérer les devises disponibles
        $currencies = Currency::all();
        
        return response()->json(['cart' => $cart, 'total' => $total, 'currencies' => $currencies]);
    }

    /**
     * Traiter le paiement et créer la commande.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'cardNumber' => 'required|string|min:16|max:16',
            'expirationDate' => 'required|date_format:m/Y',
            'cvv' => 'required|string|min:3|max:4',
            'currency_id' => 'required|exists:currencies,id',
        ]);
        
        if (!auth()->check()) {
            return response()->json(['error' => 'Veuillez vous connecter pour finaliser votre commande.'], 401);
        }
        
        $cart = Cart::where('user_id', auth()->id())->first();
        
        if (!$cart || $cart->products->isEmpty()) {
            return response()->json(['error' => 'Votre panier est vide.'], 400);
        }
        
        $command = new Command([
            'commandDate' => Carbon::now(),
            'status' => 'En attente',
            'user_id' => auth()->id(),
        ]);
        
        $command->save();
        
        $total = 0;
        foreach ($cart->products as $product) {
            $price = $product->price;
            
            if ($product->percent > 0) {
                $price = $price - ($price * $product->percent / 100);
            }
            
            $commandDetail = new CommandDetail([
                'quantity' => $product->pivot->quantity,
                'unitPrice' => $price,
                'product_id' => $product->id,
                'product_variation_id' => $product->pivot->product_variation_id,
            ]);
            
            $command->details()->save($commandDetail);
            
            $total += $price * $product->pivot->quantity;
        }
        
        $payment = new Paiement([
            'amount' => $total,
            'status' => 'Payé',
            'paiementDate' => Carbon::now(),
            'paiementMethod' => 'Carte bancaire',
            'currency_id' => $request->currency_id,
        ]);
        
        $command->payments()->save($payment);
        
        $expirationDate = Carbon::createFromFormat('m/Y', $request->expirationDate)->endOfMonth();
        
        $cardInfo = new CardInfo([
            'cardNumber' => $request->cardNumber,
            'expirationDate' => $expirationDate,
            'cvv' => $request->cvv,
        ]);
        
        $payment->cardInfo()->save($cardInfo);
        
        $cart->products()->detach();
        
        return response()->json(['success' => 'Paiement effectué avec succès.', 'command' => $command]);
    }


/**
 * Afficher la page de confirmation de commande.
 *
 * @param  int  $commandId
 * @return \Illuminate\Http\Response
 */
public function success($commandId)
{
    $command = Command::with(['details.product', 'payments'])->findOrFail($commandId);
    
    // Vérifier que la commande appartient à l'utilisateur connecté
    if ($command->user_id !== auth()->id()) {
        return response()->json(['error' => 'Accès interdit'], 403); // Retourne une réponse JSON avec une erreur 403
    }

    return response()->json([
        'success' => 'Commande confirmée avec succès.',
        'command' => $command,
    ]);
}
}