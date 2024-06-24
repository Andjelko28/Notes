<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $userAttributes = $request->validate([
            'email' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::create($userAttributes);

        Auth::login($user);
        return response()->json(['message' => 'Registration successful'], 200);
    }

}
