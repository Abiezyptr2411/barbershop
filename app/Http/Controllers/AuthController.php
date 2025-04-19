<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function registerForm() {
        return view('auth.register');
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Pendaftaran berhasil!');
    }

    public function loginForm() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            session(['user' => $user]);
            return redirect('/dashboard')->with('success', 'Login berhasil! Selamat datang, ' . $user->name);
        }
    
        return back()->with('error', 'Email atau password salah');
    }    

    public function logout() {
        session()->forget('user');
        return redirect('/login');
    }
}

