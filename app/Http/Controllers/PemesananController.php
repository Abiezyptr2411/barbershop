<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemesanan;
use Midtrans\Notification;
use Illuminate\Support\Str;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Transaction;
use App\Enums\TransactionStatus;
use App\Enums\MidtransStatusCode;

class PemesananController extends Controller
{
    public function index(Request $request)
    {
        if (!session('user')) return redirect('/login');

        $status = $request->query('status');
        $tanggal_awal = $request->query('tanggal_awal');
        $tanggal_akhir = $request->query('tanggal_akhir');

        $query = Pemesanan::where('user_id', session('user')->id);

        if ($status && in_array($status, [TransactionStatus::SETTLEMENT->value, TransactionStatus::PENDING->value])) {
            $query->where('status', $status);
        }

        if ($tanggal_awal) {
            $query->whereDate('jadwal', '>=', $tanggal_awal);
        }

        if ($tanggal_akhir) {
            $query->whereDate('jadwal', '<=', $tanggal_akhir);
        }

        $pemesanans = $query->orderByDesc('jadwal')->get();

        return view('pemesanans.index', compact('pemesanans', 'status', 'tanggal_awal', 'tanggal_akhir'));
    }

    public function create()
    {
        if (!session('user')) return redirect('/login');
        return view('pemesanans.create');
    }

    public function store(Request $request)
    {
        if (!session('user')) return redirect('/login');

        $request->validate([
            'jadwal' => 'required|date',
        ]);

        // Buat data pemesanan awal
        $pemesanan = Pemesanan::create([
            'user_id' => session('user')->id,
            'jadwal' => $request->jadwal,
            'harga' => 20000,
            'status' => TransactionStatus::PENDING->value,
            'midtrans_status_code' => null,
            'payment_type' => null,
            'bank_channel' => null,
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
                'gross_amount' => $pemesanan->harga,
            ],
            'customer_details' => [
                'first_name' => session('user')->nama,
                'email' => session('user')->email,
            ],
            'callbacks' => [
                'finish' => url('/pemesanans/finish')
            ]
        ];

        $snapUrl = Snap::createTransaction($params)->redirect_url;
        return redirect($snapUrl);
    }

    public function finish(Request $request)
    {
        $orderId = $request->order_id;

        // Setup Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $transaction = (object) Transaction::status($orderId);
        $status = TransactionStatus::tryFrom($transaction->transaction_status ?? null) ?? TransactionStatus::PENDING;
        $statusCode = MidtransStatusCode::tryFrom($transaction->status_code ?? null) ?? MidtransStatusCode::PENDING;

        $paymentType = $transaction->payment_type ?? null;
        $bank = null;

        if (!empty($transaction->va_numbers)) {
            $bank = $transaction->va_numbers[0]->bank ?? null;
        } elseif ($paymentType === 'echannel') {
            $bank = 'mandiri';
        } elseif ($paymentType === 'qris') {
            $bank = 'qris';
        }

        $pemesanan = Pemesanan::where('kode_invoice', $orderId)->first();
        if ($pemesanan) {
            $pemesanan->update([
                'status' => $status->value,
                'midtrans_status_code' => $statusCode->value,
                'payment_type' => $paymentType,
                'bank_channel' => $bank,
            ]);
        }

        return redirect('/pemesanans')->with('success', 'Pembayaran berhasil!');
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

        $transaction = TransactionStatus::tryFrom($notif->transaction_status);
        $statusCode = MidtransStatusCode::tryFrom($notif->status_code);
        $orderId = $notif->order_id;

        $pemesanan = Pemesanan::where('kode_invoice', $orderId)->first();

        if (!$pemesanan) {
            return response()->json(['message' => 'Pemesanan tidak ditemukan'], 404);
        }

        if ($transaction === TransactionStatus::SETTLEMENT || $transaction === TransactionStatus::SUCCESS) {
            $pemesanan->update([
                'status' => TransactionStatus::SUCCESS->value,
                'midtrans_status_code' => $statusCode?->value,
            ]);
        }

        return response()->json(['message' => 'Notifikasi diterima']);
    }

    public function bayarUlang($id)
    {
        if (!session('user')) return redirect('/login');

        $pemesanan = Pemesanan::findOrFail($id);

        if ($pemesanan->user_id != session('user')->id) {
            abort(403);
        }

        $kodeBaru = strtoupper(Str::random(8));
        $pemesanan->kode_invoice = $kodeBaru;
        $pemesanan->save();

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $pemesanan->kode_invoice,
                'gross_amount' => $pemesanan->harga,
            ],
            'customer_details' => [
                'first_name' => session('user')->nama,
                'email' => session('user')->email,
            ],
            'callbacks' => [
                'finish' => url('/pemesanans/finish')
            ]
        ];

        $snapUrl = Snap::createTransaction($params)->redirect_url;
        return redirect($snapUrl);
    }
}
