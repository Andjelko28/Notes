<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $userAttributes = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (!Auth::attempt($userAttributes)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => 'Sorry, those credentials do not match',
                'password' => 'Sorry, those credentials do not match'
            ]);
        }
    }

}
