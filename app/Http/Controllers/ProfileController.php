<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Command;

class ProfileController extends Controller
{
    /**
     * Afficher la page de profil de l'utilisateur.
     */
    public function show()
    {
        $user = Auth::user();
        return response()->json(['user' => $user]);
    }

    /**
     * Afficher le formulaire de modification du profil.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Mettre à jour les informations du profil.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'email' => $request->email,
        ]);

        return response()->json(['success' => 'Profil mis à jour avec succès.']);

    }

    /**
     * Afficher le formulaire de modification du mot de passe.
     */
    public function editPassword()
    {
        return view('profile.edit-password');
    }

    /**
     * Mettre à jour le mot de passe.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Vérifier que le mot de passe actuel est correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Le mot de passe actuel est incorrect.',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.show')
            ->with('success', 'Votre mot de passe a été mis à jour avec succès.');
    }

    /**
     * Afficher l'historique des commandes de l'utilisateur.
     */
    public function orders()
    {
        $user = Auth::user();
        $orders = Command::where('user_id', $user->id)
            ->orderBy('commandDate', 'desc')
            ->paginate(10);

            return response()->json(['orders' => $orders]);
        }

    /**
     * Afficher les détails d'une commande.
     */
    public function orderDetails($id)
    {
        $user = Auth::user();
        $order = Command::with(['details.product', 'payments'])
            ->where('user_id', $user->id)
            ->findOrFail($id);

            return response()->json(['order' => $order]);
        }
}
