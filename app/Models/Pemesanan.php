<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    protected $fillable = [
        'user_id',
        'jadwal',
        'harga',
        'status',
        'kode_invoice',
        'midtrans_status_code',
    ];
}


