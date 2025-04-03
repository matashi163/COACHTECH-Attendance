<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function viewRegister()
    {
        return view('auth.register');
    }

    public function viewLogin()
    {
        Auth::guard('web')->logout();
        Auth::guard('admin')->logout();

        return view('auth.login');
    }

    public function viewLoginAdmin()
    {
        Auth::guard('web')->logout();
        Auth::guard('admin')->logout();

        return view('auth.admin_login');
    }
}
