<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        return 'Login logic here';
    }

    public function logout()
    {
        return 'Logout logic here';
    }
}
