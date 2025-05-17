<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class GoogleAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string',
            'uid' => 'required|string',
            'avatar' => 'nullable|url'
        ]);

        // Cherche ou crée l'utilisateur
        $user = User::firstOrCreate(
             ['google_uid' => $request->uid],
            ['email' => $request->email],
            [
                'name' => $request->name,
                'password' => Hash::make(Str::random(24)), // mot de passe aléatoire
                'google_uid' => $request->uid,
                'avatar' => $request->avatar,
            ]
        );

        // Générer un token API
        $token = $user->createToken('googleToken')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }
}
