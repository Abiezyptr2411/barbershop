<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
            // Cek user is admin
            if ($user->is_admin) {
                return back()->with('error', 'Akun ini adalah admin. Silakan login di halaman admin.');
            }
    
            session(['user' => $user]);
            return redirect('/dashboard')->with('success', 'Login berhasil! Selamat datang, ' . $user->name);
        }
    
        return back()->with('error', 'Email/nama atau password salah');
    }        
    
    public function adminLoginForm()
    {
        return view('auth.admin-login');
    }

    public function adminLogin(Request $request)
    {
        $login = $request->email;
        $user = User::where(function ($query) use ($login) {
            $query->where('email', $login)
                ->orWhere('name', strtoupper($login));
        })->where('is_admin', 1)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            session(['admin' => $user]);
            return redirect('/admin/dashboard')->with('success', 'Login admin berhasil! Selamat datang, ' . $user->name);
        }

        return back()->with('error', 'Email/nama atau password salah atau bukan admin');
    }

    // admin dashboard
    public function adminDashboard()
    {
        if (!session('admin')) {
            return redirect('/admin/login')->with('error', 'Silakan login sebagai admin');
        }

        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth()->toDateString();
        $endOfMonth = $now->copy()->endOfMonth()->toDateString();

        // Hitung total transaksi dan volume bulan ini
        $totalTransactions = DB::table('pemesanans')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();

        $totalVolume = DB::table('pemesanans')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('harga');

        // Data chart (per tanggal)
        $chartData = DB::table('pemesanans')
            ->select('bank_channel', DB::raw('SUM(harga) as total'))
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->groupBy('bank_channel')
            ->orderBy('total', 'desc')
            ->get();

        return view('admin.dashboard', compact('totalTransactions', 'totalVolume', 'chartData'));
    }

    // transaction
    public function adminTransactions(Request $request)
    {
        if (!session('admin')) {
            return redirect('/admin/login')->with('error', 'Silakan login sebagai admin');
        }

        $query = DB::table('pemesanans')
        ->join('users', 'pemesanans.user_id', '=', 'users.id')
        ->select('pemesanans.*', 'users.name', 'users.email');

        if ($request->filled('search')) {
            $field = $request->get('field');
            $search = $request->get('search');

            if ($field === 'kode_invoice') {
                $query->where('kode_invoice', 'LIKE', "%$search%");
            } elseif ($field === 'status') {
                $query->where('status', 'LIKE', "%$search%");
            } elseif ($field === 'email') {
                $query->where('email', 'LIKE', "%$search%");
            }
        }

        if ($request->filled('date_start')) {
            $query->whereDate('created_at', '>=', $request->get('date_start'));
        }

        if ($request->filled('date_end')) {
            $query->whereDate('created_at', '<=', $request->get('date_end'));
        }

        $transactions = $query->orderBy('created_at', 'desc')->get();

        return view('admin.transactions', compact('transactions'));
    } 
    
    public function logout() {
        session()->forget('user');
        return redirect('/login');
    }
}

