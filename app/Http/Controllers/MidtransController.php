<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemesanan;
use Midtrans\Notification;

class MidtransController extends Controller
{
    

public function notification(Request $request)
    {
        // Inisialisasi konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        // Tangkap notifikasi dari Midtrans
        $notif = new Notification();

        $status = $notif->transaction_status;
        $type = $notif->payment_type;
        $orderId = $notif->order_id;
        $fraud = $notif->fraud_status;

        // Temukan pemesanan berdasarkan kode_invoice
        $pemesanan = Pemesanan::where('kode_invoice', $orderId)->first();

        if (!$pemesanan) return response()->json(['message' => 'Pemesanan tidak ditemukan'], 404);

        // Update status berdasarkan status pembayaran
        if ($status == 'capture' || $status == 'settlement') {
            $pemesanan->status = 'sukses';
            $pemesanan->save();
            return response()->json(['message' => 'Berhasil update status pemesanan'], 200);
        } elseif ($status == 'pending') {
            $pemesanan->status = 'menunggu';
            $pemesanan->save();
            return response()->json(['message' => 'Menunggu pembayaran'], 200);
        } elseif ($status == 'deny' || $status == 'cancel' || $status == 'expire') {
            $pemesanan->status = 'gagal';
            $pemesanan->save();
            return response()->json(['message' => 'Pembayaran gagal'], 200);
        }

        return response()->json(['message' => 'Status tidak dikenali'], 400);
    }
}

