<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

       public function showRegisterForm()
    {
        return view('auth.register');
    }
    

    public function register(Request $request)
    {

        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
           //telephone' => 'required|string|max:15',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
           //telephone' => $request->telephone,
            'password' => bcrypt($request->password),
        ]);

        return response()->json(['user' => $user], 201);
    }
   public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if (!auth()->attempt($request->only('email', 'password'))) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $user = auth()->user();
    $token = $user->createToken('Personal Access Token')->plainTextToken;

    return response()->json([
        'user' => $user,
        'token' => $token
    ]);
}


    public function signOut(Request $request)
    {
        $user = Auth::user();
        if ($user && $user->cart) {
            $user->tokens()->delete();
            
            return response()->json(['message' => 'Logged out successfully']);
        }

        return response()->json(['message' => 'User not found'], 404);
    }





    //MOdifier le profikl de litilisateur


public function updateProfile(Request $request)
{
    $user = Auth::user(); // Récupère l'utilisateur connecté
    
    $request->validate([
        'name' => 'sometimes|string|max:255',
        'email' => 'sometimes|email|max:255|unique:users,email,' . $user->id,
    ]);

    $user->update($request->only(['name', 'email']));
    
    return response()->json(['user' => $user, 'message' => 'Profil mis à jour !']);
}


//modifier le password


public function changePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required|string',
        'new_password' => 'required|string|min:8|confirmed',
    ]);

    $user = Auth::user();

    // Vérifie l'ancien mot de passe
    if (!Hash::check($request->current_password, $user->password)) {
        return response()->json(['error' => 'Le mot de passe actuel est incorrect'], 401);
    }

    // Met à jour le mot de passe
    $user->update([
        'password' => Hash::make($request->new_password)
    ]);

    return response()->json(['message' => 'Mot de passe mis à jour avec succès']);
}
// app/Http/Controllers/AuthController.php

public function showLoginForm()
{
    return view('auth.login'); // Crée une vue resources/views/auth/login.blade.php
}

}







