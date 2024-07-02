<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('AuthToken')->accessToken;
            return response()->json(['token' => $token]);
        }
        return response()->json(['error' => ['email' => 'These credentials do not match our records']], 422);
    }

    public function destroy(Request $request)
    {

        if (Auth::check() && $request->user()->token()) {
            $request->user()->token()->revoke();
        }
        Auth::logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
}
