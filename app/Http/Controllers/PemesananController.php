<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemesanan;
use Midtrans\Notification;
use Illuminate\Support\Str;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Transaction;

class PemesananController extends Controller
{
    public function index(Request $request)
    {
        if (!session('user')) return redirect('/login');

        $status = $request->query('status');

        $query = Pemesanan::where('user_id', session('user')->id);

        if ($status && in_array($status, ['sukses', 'batal', 'menunggu'])) {
            $query->where('status', $status);
        }

        $pemesanans = $query->get();

        // Update status dari Midtrans jika masih "menunggu"
        foreach ($pemesanans as $pemesanan) {
            if ($pemesanan->status === 'menunggu') {
                try {
                    $statusMidtrans = \Midtrans\Transaction::status($pemesanan->kode_invoice);
                    $transaction = is_object($statusMidtrans) ? $statusMidtrans->transaction_status : null;

                    if ($transaction) {
                        if (in_array($transaction, ['capture', 'settlement'])) {
                            $pemesanan->status = 'sukses';
                        } elseif (in_array($transaction, ['cancel', 'deny', 'expire'])) {
                            $pemesanan->status = 'batal';
                        }

                    
                        $pemesanan->midtrans_status_code = $statusMidtrans->status_code ?? null;
                        $pemesanan->save();
                    }      
                } catch (\Exception $e) {
                    // Log error jika gagal cek status midtrans
                }
            }
        }

        return view('pemesanans.index', compact('pemesanans', 'status'));
    }

    public function create()
    {
        if (!session('user')) return redirect('/login');
        return view('pemesanans.create');
    }

    public function store(Request $request)
    {
        if (!session('user')) return redirect('/login');
        $request->validate(['jadwal' => 'required|date']);

        // Buat data pemesanan dulu (status: menunggu)
        $pemesanan = Pemesanan::create([
            'user_id' => session('user')->id,
            'jadwal' => $request->jadwal,
            'harga' => 50000,
            'status' => 'Sukses', 
            'midtrans_status_code' => 200, 
            'kode_invoice' => strtoupper(Str::random(6)),
        ]);

        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Parameter transaksi
        $params = [
            'transaction_details' => [
                'order_id' => $pemesanan->kode_invoice,
                'gross_amount' => 50000,
            ],
            'customer_details' => [
                'first_name' => session('user')->nama,
                'email' => session('user')->email,
            ],
        ];

        // Snap token
        $snapToken = Snap::getSnapToken($params);

        return view('pemesanans.bayar', compact('snapToken', 'pemesanan'));
    }

    public function invoice($id)
    {
        if (!session('user')) return redirect('/login');
        $p = Pemesanan::findOrFail($id);
        if ($p->user_id != session('user')->id) abort(403);
        return view('pemesanans.invoice', compact('p'));
    }

    public function midtransCallback(Request $request)
    {
        $notif = new Notification();

        $transaction = $notif->transaction_status;
        $statusCode = $notif->status_code;
        $orderId = $notif->order_id;

        // Cari pemesanan berdasarkan kode_invoice
        $pemesanan = Pemesanan::where('kode_invoice', $orderId)->first();

        if (!$pemesanan) {
            return response()->json(['message' => 'Pemesanan tidak ditemukan'], 404);
        }

        // Cek apakah transaksi sukses
        if ($transaction == 'settlement' || $transaction == 'capture') {
            $pemesanan->update([
                'status' => 'sukses',
                'midtrans_status_code' => $statusCode,
            ]);
        }

        return response()->json(['message' => 'Notifikasi diterima']);
    }
}

