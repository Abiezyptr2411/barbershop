<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daftar Pemesanan - Berkah Barbershop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fc;
        }
        .card-order {
            border-radius: 1rem;
            border: 1px solid #e3e6f0;
            transition: 0.3s;
        }
        .card-order:hover {
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.1);
        }
        .status-tag {
            font-size: 0.85rem;
            padding: 0.2rem 0.6rem;
            border-radius: 0.5rem;
            font-weight: 500;
        }
        .status-menunggu {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-sukses {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        .status-batal {
            background-color: #f8d7da;
            color: #842029;
        }
        .nav-tabs .nav-link.active {
            background-color: #fd7e14;
            color: white;
            font-weight: 600;
            border: none;
            border-bottom: 3px solid #fd7e14;
            border-radius: 0.5rem 0.5rem 0 0;
        }
        .nav-tabs .nav-link {
            color: #6c757d;
            border: none;
            border-bottom: 2px solid transparent;
        }
        .nav-tabs .nav-link:hover {
            border-bottom: 2px solid #fd7e14;
        }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Rincian Pesanan</h3>
        <a href="/dashboard" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <ul class="nav nav-tabs mb-4" role="tablist">
        <li class="nav-item" role="presentation">
            <a href="?status=&tanggal_awal={{ request('tanggal_awal') }}&tanggal_akhir={{ request('tanggal_akhir') }}" class="nav-link {{ empty($status) ? 'active' : '' }}">Semua</a>
        </li>
        <li class="nav-item" role="presentation">
            <a href="?status=settlement&tanggal_awal={{ request('tanggal_awal') }}&tanggal_akhir={{ request('tanggal_akhir') }}" class="nav-link {{ $status === 'settlement' ? 'active' : '' }}">Pesanan Sukses</a>
        </li>
        <li class="nav-item" role="presentation">
            <a href="?status=pending&tanggal_awal={{ request('tanggal_awal') }}&tanggal_akhir={{ request('tanggal_akhir') }}" class="nav-link {{ $status === 'pending' ? 'active' : '' }}">Menunggu Pembayaran</a>
        </li>
    </ul>

    <form id="filterForm" class="row g-2 mb-4 align-items-end" method="GET">
        <input type="hidden" name="status" value="{{ request('status') }}">
        <div class="col-md-4">
            <label class="form-label">Tanggal Awal</label>
            <input type="date" name="tanggal_awal" class="form-control" value="{{ request('tanggal_awal') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Tanggal Akhir</label>
            <input type="date" name="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
        </div>
        <div class="col-md-2 d-grid">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-funnel-fill"></i> Filter
            </button>
        </div>
        <div class="col-md-2 d-grid">
            <button type="button" class="btn btn-outline-secondary" onclick="clearFilter()">
                <i class="bi bi-x-circle"></i> Clear Filter
            </button>
        </div>
    </form>

    <div class="row g-3">
        @forelse($pemesanans as $p)
            <div class="col-md-6 col-lg-4">
                <div class="card card-order p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        @php
                            $badge = match($p->status) {
                                'pending' => 'status-menunggu',
                                'settlement' => 'status-sukses',
                                'cancel' => 'status-batal',
                                default => 'bg-secondary text-white'
                            };
                        @endphp
                        <span class="status-tag {{ $badge }}">{{ ucfirst($p->status) }}</span>
                        <span class="text-muted" style="font-size: 0.85rem;">#{{ $p->kode_invoice }}</span>
                    </div>
                    <h5 class="mb-1">Rp{{ number_format($p->harga) }}</h5>
                    <p class="mb-2 text-muted"><i class="bi bi-calendar-event"></i> {{ \Carbon\Carbon::parse($p->jadwal)->format('d M Y, H:i') }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="/invoice/{{ $p->id }}" class="btn btn-sm btn-outline-secondary rounded-pill mt-2">
                            <i class="bi bi-receipt-cutoff"></i> Cetak Struk
                        </a>

                        @if($p->status == 'pending')
                            <a href="/pemesanans/bayar-ulang/{{ $p->id }}" class="btn btn-sm btn-outline-success rounded-pill mt-2">
                                <i class="bi bi-cash-coin"></i> Bayar Sekarang
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info">Belum ada pemesanan untuk filter ini.</div>
            </div>
        @endforelse
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: '{{ session("success") }}',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        background: '#d4edda',
        color: '#155724',
    });
</script>
@endif

<script>
    function clearFilter() {
        const form = document.getElementById('filterForm');
        form.querySelector('input[name="tanggal_awal"]').value = '';
        form.querySelector('input[name="tanggal_akhir"]').value = '';
        form.submit(); 
    }
</script>

</body>
</html>
