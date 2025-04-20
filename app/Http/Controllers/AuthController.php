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
    
        $user = User::create([
            'name' => strtoupper($request->name),
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    
        // set user logged
        session(['user' => $user]);
    
        return redirect('/dashboard')->with('success', 'Pendaftaran berhasil! Selamat datang, ' . $user->name);
    }    

    public function loginForm() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $login = $request->email; 
        $user = User::where(function ($query) use ($login) {
            $query->where('email', $login)
                  ->orWhere('name', strtoupper($login)); 
        })->first();
    
        if ($user && Hash::check($request->password, $user->password)) {
            session(['user' => $user]);
            return redirect('/dashboard')->with('success', 'Login berhasil! Selamat datang, ' . $user->name);
        }
    
        return back()->with('error', 'Email/nama atau password salah');
    }          

    public function logout() {
        session()->forget('user');
        return redirect('/login');
    }
}

