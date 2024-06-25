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

        $token = $user->createToken('AuthToken')->accessToken;


        return response()->json(['token' => $token], 200);
    }

}
