<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function authenticated(Request $request, $user)
    {
        if ($user->role === 'admin') {
            return redirect('/admin');
        } elseif ($user->role === 'penjual') {
            return redirect('/penjual/dashboard');
        } elseif ($user->role === 'pembeli') {
            return redirect('/');
        }

        return redirect('/');
    }
}
