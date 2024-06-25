<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $userAttributes = $request->validate([
            'email' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::create($userAttributes);

        if (!$user) {
            return response()->json(['error' => 'User registration failed'], 500);
        }

        try {
            $token = $user->createToken('AuthToken')->accessToken;
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token creation failed'], 500);
        }

        return response()->json(['token' => $token], 200);
    }

}
