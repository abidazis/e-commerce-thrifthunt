<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class RegisterController extends Controller
{
    public function showPembeliForm()
    {
        return view('auth.register-pembeli');
    }

    public function showPenjualForm()
    {
        return view('auth.register-penjual');
    }

    public function registerPembeli(Request $request)
    {
       $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pembeli',
        ]);

        return redirect('/login')->with('success', 'Akun pembeli berhasil dibuat!');
    }

    public function registerPenjual(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'penjual',
        ]);

        return redirect('/login')->with('success', 'Akun penjual berhasil dibuat!');
    }
}
