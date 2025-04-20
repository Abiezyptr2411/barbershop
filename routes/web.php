<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\PemesananController;

Route::get('/', fn() => redirect('/login'));

Route::get('/register', [AuthController::class, 'registerForm']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'loginForm']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);

Route::get('/dashboard', function () {
    if (!session('user')) return redirect('/login');
    return view('dashboard');
});

Route::get('/pemesanans', [PemesananController::class, 'index']);
Route::get('/pemesanans/create', [PemesananController::class, 'create']);
Route::post('/pemesanans', [PemesananController::class, 'store']);
Route::get('/invoice/{id}', [PemesananController::class, 'invoice']);

Route::post('/midtrans/callback', [MidtransController::class, 'callback']);
Route::get('/pemesanan/cek-status/{orderId}', [PemesananController::class, 'cekStatusMidtrans']);
Route::get('/pemesanans/finish', [PemesananController::class, 'finish']);

// Admin routes
Route::get('/admin/login', [AuthController::class, 'adminLoginForm']);
Route::post('/admin/login', [AuthController::class, 'adminLogin']);

// Route::get('/admin/dashboard', function () {
//     if (!session('admin')) {
//         return redirect('/admin/login')->with('error', 'Silakan login sebagai admin');
//     }
//     return view('admin.dashboard');
// });

Route::get('/admin/dashboard', [AuthController::class, 'adminDashboard']);
Route::get('/admin/transactions', [AuthController::class, 'adminTransactions']);

Route::get('/admin/logout', function () {
    session()->forget('admin');
    return redirect('/admin/login')->with('success', 'Logout berhasil');
});

